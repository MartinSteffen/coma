/*
 * Created on 24.01.2005
 *
 */
package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;
import java.sql.ResultSet;
import java.util.Collection;

import java.util.Iterator;
import java.util.Vector;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.xml.transform.stream.StreamSource;

import coma.entities.AllocP_Paper;
import coma.entities.AllocP_PaperList;
import coma.entities.AllocP_Person;
import coma.entities.Allocation;
import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchCriteria;
import coma.entities.Topic;

import coma.entities.SearchResult;
import coma.handler.db.DeleteService;
import coma.handler.db.InsertService;
import coma.handler.db.ReadService;
import coma.servlet.util.Navcolumn;
import coma.servlet.util.PageStateHelper;
import coma.servlet.util.SessionAttribs;
import coma.servlet.util.XMLHelper;

/**
 * @author ald
 *
 * Servlet to allocate papers to the reviewer.
 */
public class AllocatePapers extends HttpServlet { 
	 
	
	AllocP_Person[] personList_high = new AllocP_Person[0];	
	AllocP_PaperList paperList_high;
	AllocP_Person[] personList = new AllocP_Person[0];	
	AllocP_PaperList paperList;
	int happiness = 0;
	int shift = 0;
	int max_happiness = 0;
	int contentment = 0;
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();
	Navcolumn myNavCol = null;
	String path = null;
	int papersPerReviewer=0;
	Topic[] topics = new Topic[0];
	int conference_id;
	ReadService db_read = new coma.handler.impl.db.ReadServiceImpl();
	DeleteService db_delete = new coma.handler.impl.db.DeleteServiceImpl();
	InsertService db_insert = new coma.handler.impl.db.InsertServiceImpl();
	
	public void doGet(HttpServletRequest request, HttpServletResponse response)
	throws ServletException, java.io.IOException {
		
		HttpSession session = request.getSession(true);
		myNavCol = new Navcolumn(request.getSession(true));
		PageStateHelper pagestate = new PageStateHelper(request);
		Person person = (Person)session.getAttribute(SessionAttribs.PERSON);
		path = getServletContext().getRealPath("");
		result.delete(0,result.length());
		if (person == null ) errorXML(response,"noSession");
		int[] per_role = person.getRole_type();
		boolean isChair = false;
		int c = 0;
		while (!isChair && c < per_role.length){
			isChair = per_role[c] == 2;
			c++;
		}
		if (!isChair) errorXML(response,"unauthorized");
		Conference conference 
		    = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
	 
		
		SearchResult resultset;
		
		
		conference_id = conference.getId();
	
		paperList = new AllocP_PaperList(conference_id
						,conference.getMin_review_per_paper());	
		
		resultset = db_read.getReviewerList(conference_id); 
		fillPersonList(resultset);
		
		resultset = db_read.getTopic(0,conference_id);
		topics = (Topic[]) resultset.getResultObj();
		
		allocatePapers();
		insertAllocInDB();
		
		String action = request.getParameter("action");
		if (action != null && action.equals("html")) printOut(response);
		else printXML(response);
						
		resetHappiness();
		happiness = 0;
		max_happiness = 0;	
		shift = 0;
		
	}
	
	
	/**
	 * 
	 */
	private void insertAllocInDB() {
		
		db_delete.deleteAllRatingsNReportsByConfID(conference_id);		
		for (int i = 0 ; i < personList.length; i++){
			int person_id = personList[i].getPersonID();
			
			Vector<AllocP_Paper> papers = personList[i].getPapers();
			for (int j = 0; j < papers.size();j++){
				AllocP_Paper paper = papers.elementAt(j);
				int paper_id = paper.getPaperID();
							
				ReviewReport report = new ReviewReport();
				report.set_paper_Id(paper_id);
				report.set_reviewer_id(person_id);
				db_insert.insertReviewReport(report);
				
				
			}
			SearchCriteria sc = new SearchCriteria();
			ReviewReport rep = new ReviewReport();
			rep.set_reviewer_id(person_id);
			sc.setReviewReport(rep);
			SearchResult sr = db_read.getReviewReport(sc);
			ReviewReport[] reports = (ReviewReport []) sr.getResultObj();
			Criterion crit = new Criterion();
			sc = new SearchCriteria();
			
			crit.setConferenceId(conference_id);
			sc.setCriterion(crit);
			sr = db_read.getCriterion(sc);
			//System.out.println(sr.getInfo());
			Criterion[] crits = (Criterion[])sr.getResultObj();

			for (int j = 0; j < reports.length; j++){
				int rep_id = reports[j].getId();
				
				
				for (int u = 0; u < crits.length;u++){
					Rating rating = new Rating();
					rating.setReviewReportId(rep_id);
					rating.set_criterion_id(crits[u].getId());
					
					db_insert.insertRating(rating);
				}
			}
		}
	}


	/**
	 * 
	 */
	private void errorXML(HttpServletResponse response,String error) {
		PrintWriter out;
		try {
			out = response.getWriter();
			response.setContentType("text/html; charset=ISO-8859-1");
			helper.addXMLHead(result);
			result.append("<stderrors>");
			result.append("<"+error+">");
			result.append("</"+error+">");
			result.append("</stderrors>");			
			
		//	System.out.println(result.toString());
			
			String xslt = path + "/style/xsl/stderrors.xsl";
		    StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
			StreamSource xsltSource = new StreamSource(xslt);
			XMLHelper.process(xmlSource, xsltSource, out);
			out.flush();
		}
		catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}


	/**
	 * @param response
	 */
	
	/*
	 * <result>
	<Navbar>
	<allocation papersPerReviewer=5>
	<persons>
		<reviewer id=xx firstname=xxx lastname=xxxx title=xxxx contentment=x>
			<prefersPaper id=1 />
			<prefersTopic id=1 />
			<paper id=1 pref=paper/>
			<paper id=1 pref=topic/>
			<paper id=1 pref=none/>
		</reviewer>
	</persons>
	<papers>
		<paper id=1 title=xxxx>
		 	<topic id=1/>
		 	<topic id=1/>
		 	<topic id=1/>
		</paper>
		<paper id=1 title=xxxx>
		 	<topic id=1/>
		 	<topic id=1/>
		 	<topic id=1/>
		</paper>
	</papers>
	<allocation>
	<topics>
		<topic id=1 name=blabla>
		<topic id=1 name=blabla>
		<topic id=1 name=blabla>
	</topics>
</result>
	 */
	private void printXML(HttpServletResponse response) {
		PrintWriter out;
		try {
			out = response.getWriter();
		
		
		response.setContentType("text/html; charset=ISO-8859-1");
		helper.addXMLHead(result);
		result.append("<result>\n");
		result.append(myNavCol+"\n");
		result.append("<allocation papersPerReviewer=\""+papersPerReviewer+"\">\n");
		result.append("<persons>\n");
		for (int i = 0 ; i < personList.length; i++){
			result.append("<reviewer ");
			result.append("id=\""+personList[i].getPersonID()+"\"");
			result.append(" firstname=\""+personList[i].getFirstName()+"\"");
			result.append(" lastname=\""+personList[i].getLastName()+"\"");
			result.append(" title=\""+personList[i].getTitle()+"\"");
			result.append(" contentment=\""+personList[i].getContent()+"\"");
			result.append(">\n");
			Vector<Integer> prefP = personList[i].getPreferdPapers();
			Vector<Integer> prefT = personList[i].getPreferdTopics();
			for (int j = 0; j < prefP.size(); j++){
				result.append("<prefersPaper id=\""+prefP.elementAt(j)+"\" />\n");				
			}
			for (int j = 0; j < prefT.size(); j++){
				result.append("<prefersTopic id=\""+prefT.elementAt(j)+"\" />\n");				
			}
			Vector<AllocP_Paper> paps = personList[i].getPapers();
			for (int j = 0; j < paps.size();j++){
				AllocP_Paper p = paps.elementAt(j);
				String pref = "none";
				if (personList[i].isPreferedPaper(p)) pref = "paper"; 
				else if (personList[i].isPreferedTopic(p)) pref = "topic";
				result.append("<paper id=\""+(p.getPaperID()
						+"\" pref=\""+pref+"\" />\n"));
			}
			result.append("</reviewer>\n");
		}
		result.append("</persons>\n");
		result.append("<papers>\n");
		Collection<AllocP_Paper> paps = paperList.getPapers();
		Iterator<AllocP_Paper> it = paps.iterator();
		while (it.hasNext()){
			AllocP_Paper p = it.next();
			result.append("<paper ");
			result.append("id=\""+p.getPaperID()+"\"");
			result.append(" title=\""+p.getTitle()+"\">\n");
			int[] tops = p.getTopicIDs();
			for (int j = 0 ; j < tops.length; j++){
				result.append("<topic id=\""+tops[j]+"\" />\n");
			}
			Vector<AllocP_Person> revs = p.getReviewer();
			for (int j = 0 ; j < revs.size();j++){
				result.append("<reviewer id=\""+revs.elementAt(j).getPersonID()+"\" />\n");
			}
			result.append("</paper>\n");
		}
		result.append("</papers>\n");
		result.append("</allocation>\n");
		result.append("<topics>\n");
		for (int i = 0 ; i < topics.length; i++){
			result.append("<topic id=\""+topics[i].getId()
					+"\" name=\""+topics[i].getName()+"\" />\n");
		}
		result.append("</topics>\n");
		result.append("</result>\n");
		
	//	System.out.println(result.toString());
		
		String xslt = path + "/style/xsl/allocate.xsl";
	    StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
		StreamSource xsltSource = new StreamSource(xslt);
		XMLHelper.process(xmlSource, xsltSource, out);
		out.flush();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}


	/**
	 * tempor�re Ausgabe
	 */
	private void printOut(HttpServletResponse res) {
		
		try {
			res.setContentType("text/html");
			PrintWriter out = res.getWriter();
			out.print("<table border=1>");
			out.print("<tr>");
			out.print("<td>Person</td>");
			out.print("<td>Pref.Paps</td>");
			out.print("<td>Pref.Tops</td>");
			out.print("<td>Papers</td>");
			out.print("<td>% Content</td>");
			out.print("<td>Content/Total</td>");
			out.print("<td>Happy/Total</td>");
			out.print("<td>TotalPaps</td>");
			out.println("</tr>");
			for (int i = 0 ; i < personList.length;i++){
				out.print("<tr>");
				AllocP_Person person = personList[i];
				out.print("<td>"+person.getPersonID()+":"+person.getFirstName()+
						" "+person.getLastName()+"</td><td>");
				Vector<Integer> pap = person.getPreferdPapers();
				for (int x = 0; x< pap.size();x++)
					out.print(pap.elementAt(x)+",");
				out.print("</td><td>");
				Vector<Integer> tops = person.getPreferdTopics();
				for (int x = 0; x< tops.size();x++)
					out.print(tops.elementAt(x)+",");
				out.print("</td><td>");
				
				Vector papers = person.getPapers();
				for (int j = 0; j < papers.size();j++){
					String in = "";
					String out_ = "";
					AllocP_Paper paper = (AllocP_Paper) papers.elementAt(j);
					if (person.isPreferedPaper(paper))
							{
								in = "<font color=green>";
								out_ = "</font>";
							}
					else if (person.isPreferedTopic(paper))
					{
						in = "<font color=blue>";
						out_ = "</font>";
					}
					out.print(in+paper.getPaperID()+"(");
					int[] t = paper.getTopicIDs();
					for (int u = 0;u<t.length;u++)
						out.print(t[u]+",");
					out.print(")"+out_+",");
				}
			
				out.println("</td><td>"+person.getPercContent()+"%");
				out.println("</td><td>"+person.getContent()+"/"+person.getNumOfPapers()+"</td><td>");
				out.println(person.getHappiness()+"/"+person.getNumOfPapers()*2);
				out.println("</td><td>"+person.getNumOfPapers());
				out.print("</td></tr>");
			}
			out.print("</table>");
			out.println("Happiness: "+happiness+"<br>");
			out.println("ContentPerc: "+contentment+"<br>");
			
			out.println("Shift: "+shift+"<br>");
			out.println("-----------"+"<br>");
			Collection<AllocP_Paper> e = paperList.getPapers();
			Iterator<AllocP_Paper> it = e.iterator();
			while (it.hasNext())
			{
				AllocP_Paper p = it.next();
				out.println("Paper: "+p.getPaperID()+" "+p.getTitle()+":"+p.getNumOfReviewer()+"<br>");
			}
			
		} catch (IOException e) {
			
			e.printStackTrace();
		}
		
	}

	/**
	 * 
	 */
	private void allocatePapers() {
		Allocation alloc = new Allocation();
		int min_diff = paperList.getMinReviewer()+1;
		while (shift < personList.length)
		{
			
			resetPapers();
			resetHappiness();
			resetContent();
			
			while (paperList.getOpenPapers()!=0) newRound(shift);
			
			fillUpPapers();
			happiness = getHappiness(personList);
			int diff = getConDiff();
			
			if (happiness >= max_happiness && min_diff > diff)
			{
				min_diff = diff;
				max_happiness = happiness;
				alloc = new Allocation(paperList,personList);
			}
			shift++;
			
		}
		resetPapers();
		resetHappiness();
		resetContent();
		alloc.reAlloc(paperList,personList);
		happiness = getHappiness(personList);
		contentment = getPercContent(personList);
		papersPerReviewer=personList[0].getNumOfPapers();
	}
	

	/**
	 * @return
	 */
	private int getConDiff() {
		int min = paperList.getMinReviewer()+1;
		int max = 0;
		for (int i = 0 ; i < personList.length ; i++){
			int c = personList[i].getContent();
				
			if (c < min) min = c;
			if (c > max) max = c;
		}
		
		return max-min;
	}


	/**
	 * @param personList2
	 * @return
	 */
	private int getPercContent(AllocP_Person[] pl) {
		int content = 0;
		for (int i = 0; i < pl.length;i++){
			pl[i].reCalcHapCon();
			content += pl[i].getPercContent();
		}
		return content/pl.length;
	}


	/**
	 * 
	 */
	private void resetContent() {
		for (int i = 0; i < personList.length;i++)
			 personList[i].resetContent();
	}


	/**
	 * 
	 */
	private void resetPapers() {
		for (int i = 0; i < personList.length;i++)
			 personList[i].resetPapers();
		paperList.resetPapers();
	}


	/**
	 * 
	 */
	private void fillUpPapers() {
		Vector<AllocP_Person> underworkpersons = new Vector<AllocP_Person>();
		int maxPapers = 0;
		for (int i = 0; i <personList.length;i++)
		{
			if (personList[i].getNumOfPapers()>maxPapers)
				maxPapers = personList[i].getNumOfPapers();
		}
		for (int i = 0; i <personList.length;i++)
		{
			if (personList[i].getNumOfPapers()< maxPapers){
					underworkpersons.add(personList[i]);
					
			}
		}
		for (int i = 0; i < underworkpersons.size();i++){
			underworkpersons.elementAt(i).pickPaper(paperList,false);
		}
		
	}


	/**
	 * @return
	 */
	private int getHappiness(AllocP_Person[] pl) {
		int happy = 0;
		for (int i = 0; i < pl.length;i++){
			pl[i].reCalcHapCon();
			happy += pl[i].getHappiness();
		}
		return happy;
	}
	/**
	 * 
	 */
	private void resetHappiness() {
		for (int i = 0; i < personList.length;i++)
			 personList[i].resetHappy();
	}

	/**
	 * @return
	 */
	private void newRound(int shift) {
		
		for (int i = 0; i < personList.length;i++){
			personList[(i+shift)%personList.length].pickPaper(paperList,true);					
		}
	}

	/**
	 * @param resultset
	 */
	private void fillPersonList(SearchResult resultset) {
		int[] list = (int[])resultset.getResultObj();
		personList = new AllocP_Person[list.length];
		for (int i = 0; i < list.length ; i++){
			personList[i] = new AllocP_Person(list[i]);
		}
		
	}

	public void doPost(HttpServletRequest request, HttpServletResponse response)
	throws ServletException, java.io.IOException {
		doGet(request,response);
	}
}
