/*
 * Created on 24.01.2005
 *
 */
package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Enumeration;
import java.util.Vector;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import coma.entities.AllocP_Paper;
import coma.entities.AllocP_PaperList;
import coma.entities.AllocP_Person;
import coma.entities.Conference;
import coma.entities.Person;
import coma.entities.SearchCriteria;
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
	 
	
	AllocP_Person[] personList = new AllocP_Person[0];	
	AllocP_PaperList paperList;
	int happiness = 0;
	int shift = 0;
	
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
		
		happiness = 0;
		resetHappiness();
		shift = (shift + 1 )%personList.length;
	}
	
	
	/**
	 * temporäre Ausgabe
	 */
	private void printOut(HttpServletResponse res) {
		
		try {
			PrintWriter out = res.getWriter();
			for (int i = 0 ; i < personList.length;i++){
				AllocP_Person person = personList[i];
				out.print("Person "+person.getPersonID()+" tops(");
				int[] tops = person.getPreferdTopics();
				for (int x = 0; x< tops.length;x++)
					out.print(tops[x]+",");
				out.print(") pap( ");
				int[] pap = person.getPreferdPapers();
				for (int x = 0; x< pap.length;x++)
					out.print(pap[x]+",");
				out.print(") Papers: ");
				Vector papers = person.getPapers();
				for (int j = 0; j < papers.size();j++){
					AllocP_Paper paper = (AllocP_Paper) papers.elementAt(j);
					out.print(paper.getPaperID()+"(");
					int[] t = paper.getTopicIDs();
					for (int u = 0;u<t.length;u++)
						out.print(t[u]+",");
					out.print("),");
				}
				out.println(" happy: "+person.getPercHappiness()+"% "+
						person.getHappiness()+"/"+person.getNumOfPapers()*2
							+" TotalPap: "+person.getNumOfPapers());
			}
			out.println("Happiness: "+happiness);
			out.println("Shift: "+shift);
			out.println("-----------");
			Enumeration<AllocP_Paper> e = paperList.getPapers();
			while (e.hasMoreElements())
			{
				AllocP_Paper p = e.nextElement();
				out.println("Paper: "+p.getPaperID()+":"+p.getNumOfReviewer());
			}
			
		} catch (IOException e) {
			
			e.printStackTrace();
		}
		
	}

	/**
	 * 
	 */
	private void allocatePapers() {
				
		while (paperList.getOpenPapers()!=0) newRound(shift);
		fillUpPapers();
		happiness = getHappiness();		
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
			if (personList[i].getNumOfPapers()<maxPapers)
				underworkpersons.add(personList[i]);
		}
		for (int i = 0; i < underworkpersons.size();i++){
			underworkpersons.elementAt(i).pickPaper(paperList,false);
		}
		
	}


	/**
	 * @return
	 */
	private int getHappiness() {
		int happy = 0;
		for (int i = 0; i < personList.length;i++)
			happy += personList[i].getHappiness();
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
