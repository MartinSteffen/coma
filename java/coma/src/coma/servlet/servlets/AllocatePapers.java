/*
 * Created on 24.01.2005
 *
 */
package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Collection;

import java.util.Iterator;
import java.util.Vector;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import coma.entities.AllocP_Paper;
import coma.entities.AllocP_PaperList;
import coma.entities.AllocP_Person;
import coma.entities.Allocation;
import coma.entities.Conference;
import coma.entities.Person;

import coma.entities.SearchResult;
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
	
	public void doGet(HttpServletRequest request, HttpServletResponse response)
	throws ServletException, java.io.IOException {
		
		HttpSession session = request.getSession(true);

		PageStateHelper pagestate = new PageStateHelper(request);
		Person person = (Person)session.getAttribute(SessionAttribs.PERSON);	
		Conference conference 
		    = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
	 
		ReadService db_read = new coma.handler.impl.db.ReadServiceImpl();
		SearchResult resultset;
		
		int conference_id = 1;  //TODO: conference_id eintragen!
		
		paperList = new AllocP_PaperList(conference_id
						,4); //TODO: min_reviewer eintragen!!	
		
		resultset = db_read.getReviewerList(conference_id); 
		fillPersonList(resultset);
		
		allocatePapers();
		//testphase TODO: wegmachen
		printOut(response);
		
		resetHappiness();
		happiness = 0;
		max_happiness = 0;	
		shift = 0;//(shift+1)%personList.length;
		
	}
	
	
	/**
	 * tempor�re Ausgabe
	 */
	private void printOut(HttpServletResponse res) {
		
		try {
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
				out.println("Paper: "+p.getPaperID()+":"+p.getNumOfReviewer()+"<br>");
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
