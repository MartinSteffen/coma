package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;
//import java.sql.Connection;
//import java.sql.ResultSet;
//import java.sql.SQLException;
//import java.sql.Statement;
//import javax.naming.Context;
//import javax.naming.InitialContext;
import javax.servlet.ServletConfig;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;
import javax.xml.transform.stream.StreamSource;

import coma.servlet.util.XMLHelper;
import coma.servlet.util.*;
import java.util.*;
import coma.entities.*;
import coma.handler.impl.db.*;
import coma.handler.impl.db.InsertServiceImpl;
//import coma.handler.db.*;

//import coma.entities.*;

//import org.apache.catalina.realm.RealmBase;


/**
 * @author Peter Kauffels & Harm Brandt
 * @version 1.0
 * The chair servlet is responsible for all functions the chair can do.
   Changes at 12.12: 	- added commit Method(at the bottom).
   by hbra@inf...	    - added EMail function(not yet for invite)
   						- changes path settings
   						- added if ( action == null) at 67
 * Changes at 14.12: 	- change method commit, now runs with parameters 
 * by pka				  HttpServletResponse res and String tag.  
 * 						- delete redundant code
 * Changes at 20.12: 	- show_papers tested with my personal db,
 * by pka				  new person(-1) let me get all persons,
 * 						  changed readserviceImpl
 *  			 		- show_authors, show_reviewers update
 * Changes at 22.12: 	- show_papers changed, is complete, also with xsl
 * Changes at 27.12: 	- show_authors and show_reviewers are complete, 
 * by pka				  also with xsl  								
 **/

public class Chair extends HttpServlet 
{
	static final long serialVersionUID = 1;
	DataSource ds = null;
	StringBuffer info = new StringBuffer();
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();
	StringBuffer status = new StringBuffer();
	String path = null;
	String user = null;
	Navcolumn myNavCol = null;

	public void init(ServletConfig config) 
	{
	}
	
	public void doGet(HttpServletRequest req,HttpServletResponse res) 
	{
		info.delete(0,info.length());
		result.delete(0,result.length());
		String action = req.getParameter("action");
		HttpSession session= req.getSession(true);
		/*
		 * FIXME: set relative path
		 */
		path = "/home/superingo/jakarta-tomcat-5.0.28/webapps/coma";
		//path = getServletContext().getRealPath("");
		Person p = (Person)session.getAttribute(SessionAttribs.PERSON);
		myNavCol = new Navcolumn(req.getSession(true));
		user = p.getFirst_name() + " " + p.getLast_name();
		if (action.equals("invite_person"))
		{	
			invite_person(req,res,session);
		}
		if (action.equals("show_authors"))
		{
			show_authors(req,res,session);
		}
		if (action.equals("show_reviewers"))
		{
			show_reviewers(req,res,session);
		}
		if (action.equals("show_papers"))
		{
			show_papers(req,res,session);
		}
		if (action.equals("setup"))
		{
			setup(req,res,session);
		}
		if (action.equals("email"))
		{
			email(req,res,session);
		}
		if (action.equals("send_invitation"))
		{
			send_invitation(req,res,session);
		}
		if (action.equals("send_setup"))
		{
			send_setup(req,res,session);
		}
		if (action.equals("send_email"))
		{
			send_email(req,res,session);
		}
	}

	public void doPost(HttpServletRequest request, HttpServletResponse response) 
	{
		doGet(request, response);
	}
			
	public void invite_person(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		info.append(XMLHelper.tagged("content",""));
		info.append(XMLHelper.tagged("status","" + user + ": invite a person\n"));
		String tag = "invite";
		commit(res,tag);
	}
	
	public void send_invitation(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		String[] formular = new String[] {req.getParameter("first name"),req.getParameter("last name")
				,req.getParameter("email")};
		FormularChecker checker = new FormularChecker(formular);
		if (checker.check())
		{
		    Password_maker password_maker = new Password_maker(req.getParameter("first name"),req.getParameter("last name"),req.getParameter("email"));
			String pass = password_maker.generate_password();
			Person p = new Person(-1);
			p.setPassword(pass);
			p.setFirst_name(formular[0]);
			p.setLast_name(formular[1]);
			p.setEmail(formular[2]);
			if (req.getParameter("invite as")=="author")
				p.setRole_type(1);
			else
				p.setRole_type(2);
			InsertServiceImpl insert = new InsertServiceImpl();
			insert.insertPerson(p);
		    info.append(XMLHelper.tagged("status","" + user + ": E-Mail successfully send to " + req.getParameter("first name") +" " +  req.getParameter("last name")));
		    info.append(XMLHelper.tagged("content",""));
		    String tag = "invitation_send";
		    commit(res,tag);
		}
		else 
		{
		    info.delete(0,info.length());
		    //info.append(XMLHelper.tagged("pageTitle","invitation send"));
		    info.append("<content>");
		    info.append(XMLHelper.tagged("first",formular[0]));
		    info.append(XMLHelper.tagged("last",formular[1]));
		    info.append(XMLHelper.tagged("email",formular[2]));
			info.append("</content>");
			info.append(XMLHelper.tagged("status","" + user + ": you have to fill out all *-fields"));
			String tag = "invite";
			commit(res,tag);
		}
	}
	
	public void setup(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		
		/*
		 * FIXME get conference from session
		 */
		Conference c = new Conference(1);
	    ReadServiceImpl readService = new ReadServiceImpl();
	    SearchCriteria search = new SearchCriteria();
	    search.setConference(c);
	    SearchResult search_result = readService.getConference(search);
	    Conference[] conferences = (Conference[])search_result.getResultObj();
	    readService = new ReadServiceImpl();
	    search = new SearchCriteria();
	    
	    /*
	     * FIXME get all topics
	     */
	    Topic t = new Topic(-2);
	    //search.setTopic(t);
	    //search_result = readService.getTopic(search);
	    //Topic[] topics = (Topic[])search_result.getResultObj();
	    /*
	     * FIXME andere Bedingung, ob conference setup fertig?
	     * Ich denke sowas wie conference.getstart==null
	     */
	    if (conferences==null || conferences.length==0)
	    {
	    	setup_step(req,res,session);
	    }
	    else
	    {
	    	String tag = "setup";
			info.append(XMLHelper.tagged("status","" + user + ": you are chair of: "));
			info.append("<content>");
			info.append(conferences[0].toXML(Entity.XMLMODE.DEEP));
			/*for (int i=0;i<topics.length;i++)
			{
				info.append(topics[i].toXML());
			}*/
			info.append("</content>");
			commit(res,tag);
	    }
	}
	
	public void setup_step(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		String tag=null;
		if (req.getParameter("step")==null)
		{
			tag = "setup_new_step1";
			info.append(XMLHelper.tagged("status","" + user + ": setup a new conference step 1"));
			info.append(XMLHelper.tagged("content",""));
		}
		else
		{
			tag = "setup_new_step2";
			info.append("<content>");
			
			for(int i=0;i<Integer.parseInt(session.getAttribute("topics").toString());i++)
			{
				info.append("<topic>");
				info.append(XMLHelper.tagged("number",i));
				info.append("</topic>");
			}
			info.append("</content>");
			info.append(XMLHelper.tagged("status","" + user + ": setup a new conference step 2"));
		}
		commit(res,tag);	
	}

	public void send_setup(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{ 
		if (req.getParameter("step").equals("update"))
		{
			GregorianCalendar calendar = null;
			/*
			 * FIXME get conference to update from session
			 */
			Conference c = new Conference(1);
			if (req.getParameter("conference name")!=null)
				c.setName(req.getParameter("conference name"));
			if (req.getParameter("homepage")!=null)
				c.setHomepage(req.getParameter("homepage"));
			if (req.getParameter("description")!=null)
				c.setDescription(req.getParameter("description"));
			if (!(req.getParameter("abstract_day").equals("")) && (req.getParameter("abstract_month").equals("")) && (req.getParameter("abstract_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("abstract_year")),
			    Integer.parseInt(req.getParameter("abstract_month"))-1,Integer.parseInt(req.getParameter("abstract_day")));
			    c.setAbstract_submission_deadline(calendar.getTime());
			}
			if (!(req.getParameter("final_day").equals("")) && (req.getParameter("final_month").equals("")) && (req.getParameter("final_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("final_year")),
			    		Integer.parseInt(req.getParameter("final_month"))-1,Integer.parseInt(req.getParameter("final_day")));
			    c.setFinal_version_deadline(calendar.getTime());
			}
			if (!(req.getParameter("paper_day").equals("")) && (req.getParameter("paper_month").equals("")) && (req.getParameter("paper_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("paper_year")),
			    		Integer.parseInt(req.getParameter("paper_month"))-1,Integer.parseInt(req.getParameter("paper_day")));
			    c.setPaper_submission_deadline(calendar.getTime());
			}
			if (!(req.getParameter("review_day").equals("")) && (req.getParameter("review_month").equals("")) && (req.getParameter("review_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("review_year")),
			    		Integer.parseInt(req.getParameter("review_month"))-1,Integer.parseInt(req.getParameter("review_day")));
			    c.setReview_deadline(calendar.getTime());
			}
			if (!(req.getParameter("not_day").equals("")) && (req.getParameter("not_month").equals("")) && (req.getParameter("not_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("not_year")),
			    		Integer.parseInt(req.getParameter("not_month"))-1,Integer.parseInt(req.getParameter("not_day")));
			    c.setNotification(calendar.getTime());
			}
			UpdateServiceImpl update = new UpdateServiceImpl();
			update.updateConference(c);
		}
		if (req.getParameter("step").equals("1"))
		{
			GregorianCalendar calendar = null;
			String[] formular = new String[] {req.getParameter("conference name"),req.getParameter("homepage"),
			req.getParameter("start_year"),req.getParameter("start_month"),req.getParameter("start_day"),
			req.getParameter("end_year"),req.getParameter("end_month"),req.getParameter("end_day"),
			req.getParameter("abstract_year"),req.getParameter("abstract_month"),req.getParameter("abstract_day"),
			req.getParameter("paper_year"),req.getParameter("paper_month"),req.getParameter("paper_day"),
			req.getParameter("final_year"),req.getParameter("final_month"),req.getParameter("final_day"),
			req.getParameter("review_year"),req.getParameter("review_month"),req.getParameter("review_day"),
			req.getParameter("not_year"),req.getParameter("not_month"),req.getParameter("not_day"),
			req.getParameter("min_reviewers"),req.getParameter("topics")};
		    FormularChecker checker = new FormularChecker(formular);
			if (checker.check())
			{
			    Conference c = new Conference(-1);
			    c.setName(req.getParameter("conference name"));
			    c.setHomepage(req.getParameter("homepage"));
			    c.setDescription(req.getParameter("description"));
			    calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("start_year")),
			    		Integer.parseInt(req.getParameter("start_month"))-1,Integer.parseInt(req.getParameter("start_day")));
			    c.setConference_start(calendar.getTime());
			    calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("end_year")),
			    		Integer.parseInt(req.getParameter("end_month"))-1,Integer.parseInt(req.getParameter("end_day")));
			    c.setConference_end(calendar.getTime());
			    calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("abstract_year")),
			    		Integer.parseInt(req.getParameter("abstract_month"))-1,Integer.parseInt(req.getParameter("abstract_day")));
			    c.setAbstract_submission_deadline(calendar.getTime());
			    calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("final_year")),
			    		Integer.parseInt(req.getParameter("final_month"))-1,Integer.parseInt(req.getParameter("final_day")));
			    c.setFinal_version_deadline(calendar.getTime());
			    calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("paper_year")),
			    		Integer.parseInt(req.getParameter("paper_month"))-1,Integer.parseInt(req.getParameter("paper_day")));
			    c.setPaper_submission_deadline(calendar.getTime());
			    calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("review_year")),
			    		Integer.parseInt(req.getParameter("review_month"))-1,Integer.parseInt(req.getParameter("review_day")));
			    c.setReview_deadline(calendar.getTime());
			    calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("not_year")),
			    		Integer.parseInt(req.getParameter("not_month"))-1,Integer.parseInt(req.getParameter("not_day")));
			    c.setNotification(calendar.getTime());
			    c.setMin_review_per_paper(Integer.parseInt(req.getParameter("min_reviewers")));
			    InsertServiceImpl insert = new InsertServiceImpl();
			    insert.insertConference(c);
			    session.setAttribute("topics",req.getParameter("topics"));
			    /*
			     * TODO insert new conference into session;
			     */
			    setup_step(req,res,session);
			}
			else
			{
				String tag="setup_new_step1";
				info.delete(0,info.length());
				info.append("<content>");
				info.append(XMLHelper.tagged("name",formular[0]));
				info.append(XMLHelper.tagged("home",formular[1]));
				info.append(XMLHelper.tagged("start_year",formular[2]));
				info.append(XMLHelper.tagged("start_month",formular[3]));
				info.append(XMLHelper.tagged("start_day",formular[4]));
				info.append(XMLHelper.tagged("end_year",formular[5]));
				info.append(XMLHelper.tagged("end_month",formular[6]));
				info.append(XMLHelper.tagged("end_day",formular[7]));
				info.append(XMLHelper.tagged("abstract_year",formular[8]));
				info.append(XMLHelper.tagged("abstract_month",formular[9]));
				info.append(XMLHelper.tagged("abstract_day",formular[10]));
				info.append(XMLHelper.tagged("paper_year",formular[11]));
				info.append(XMLHelper.tagged("paper_month",formular[12]));
				info.append(XMLHelper.tagged("paper_day",formular[13]));
				info.append(XMLHelper.tagged("final_year",formular[14]));
				info.append(XMLHelper.tagged("final_month",formular[15]));
				info.append(XMLHelper.tagged("final_day",formular[16]));
				info.append(XMLHelper.tagged("review_year",formular[17]));
				info.append(XMLHelper.tagged("review_month",formular[18]));
				info.append(XMLHelper.tagged("review_day",formular[19]));
				info.append(XMLHelper.tagged("not_year",formular[20]));
				info.append(XMLHelper.tagged("not_month",formular[21]));
				info.append(XMLHelper.tagged("not_day",formular[22]));
				info.append(XMLHelper.tagged("min",formular[23]));
				info.append(XMLHelper.tagged("topics",formular[24]));
				info.append("</content>");
				info.append(XMLHelper.tagged("status","" + user + ": please fill out all *-fields"));
				commit(res,tag);
			}
		}
		
		if (req.getParameter("step").equals("2"))
		{
			int topics = Integer.parseInt(session.getAttribute("topics").toString());
			session.setAttribute("topics",null);
			/*
			 * TODO topic der conference zuordnen und in die Datenbank einfügen
			 */
			for(int i=0;i<topics;i++)
			{
				if (req.getParameter("topic"+i)==null)
					break;
				InsertServiceImpl insert = new InsertServiceImpl();
			    insert.insertTopic(1,req.getParameter("topic"+i));
			}
			setup(req,res,session);
		}
	}
	
	public void show_authors(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		String tag;
		Person p;
		boolean PAPER = false;
		if(req.getParameter("delete")!=null)
		{
			/*FIXME
			 * delete person from db
			 */
			try
			{
			res.sendRedirect("/coma/Chair?action=show_authors");
			}
			catch(IOException io)
			{
				// TODO handle Exception
			}
		}
		if (req.getParameter("id")==null)
		{
			p = new Person(-1);
			p.setRole_type(1);
			tag = "showauthors";
		}
		else
		{
			p = new Person(Integer.parseInt(req.getParameter("id")));
			PAPER=true;
			tag = "showauthors_data";
		}
        SearchCriteria search = new SearchCriteria();
        search.setPerson(p);
        ReadServiceImpl readService = new ReadServiceImpl();
        SearchResult search_result = readService.getPerson(search);
        Person[] result = (Person[])search_result.getResultObj();
		if (result==null || result.length ==0)
        {
			info.append(XMLHelper.tagged("status","" + user + ": no authors available"));
        	commit(res,tag);
        }
        else
        {
        	info.append("<content>");
        	for (int i=0;i<result.length;i++)
        	{
        		p = result[i];
        		info.append(p.toXML());
        	}
        	if (PAPER)
        	{
        		try
        		{
        			Paper[] personPaper = p.getPapers();
	        		for (int i=0;i<personPaper.length;i++)
	        		{
	        			info.append(personPaper[i].toXML());
	        		}
        		}
        		catch(Exception e)
        		{
        			System.out.println(e.toString());
        		}
	        		
        	}
        	info.append("</content>");
        	if (PAPER)

        		info.append(XMLHelper.tagged("status","" + user + ": statistic for author "+ p.getFirst_name()+" "+p.getLast_name()));	
        	else
        		info.append(XMLHelper.tagged("status","" + user + ": list of all authors"));	
        	commit(res,tag);
        }
	}

	public void show_reviewers(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		String tag;
		if(req.getParameter("delete")!=null)
		{
			/*FIXME
			 * delete person from db
			 */
			try
			{
			res.sendRedirect("/coma/Chair?action=show_reviewers");
			}
			catch(IOException io)
			{
				
			}
		}
		else
		{
			if(req.getParameter("id")!=null)
			{
				tag = "showreviewers_data";
				info.append(XMLHelper.tagged("status","" + user + ":statistic"));
				Person p = new Person(Integer.parseInt(req.getParameter("id")));
				SearchCriteria search = new SearchCriteria();
		        search.setPerson(p);
		        ReadServiceImpl readService = new ReadServiceImpl();
		        SearchResult search_result = readService.getPerson(search);
		        Person[] result = (Person[])search_result.getResultObj();
		        if (result==null || result.length ==0)
		        {
			 		info.append(XMLHelper.tagged("status","" + user + ": no reviewer available"));
		        	commit(res,tag);
		        }
		        else
		        {
		        	info.append("<content>");
		        	for (int i=0;i<result.length;i++)
		        	{
		        		p = result[i];
		        		info.append(p.toXML());
		        	}
		        	info.append("</content>");
		        	commit(res,tag);	
		        }
			}
			else
			{
				tag = "showreviewers";
		        Person p = new Person(0);
		        p.setRole_type(2);
		        SearchCriteria search = new SearchCriteria();
		        search.setPerson(p);
		        ReadServiceImpl readService = new ReadServiceImpl();
		        SearchResult search_result = readService.getPerson(search);
		        Person[] result = (Person[])search_result.getResultObj();
				 if (result==null || result.length ==0)
			        {
				 		info.append(XMLHelper.tagged("status","" + user + ": no reviewers available"));
			        	commit(res,tag);
			        }
			        else
			        {
			        	info.append("<content>");
			        	for (int i=0;i<result.length;i++)
			        	{
			        		p = result[i];
			        		info.append(p.toXML());
			        	}
			        	info.append("</content>");
			        	info.append(XMLHelper.tagged("status","" + user + ": list of all reviewers"));
			        	commit(res,tag);	
			        }
			}
		}
	}
	
	public void show_papers(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		/*
		 * FIXME get ReviewReports and Rating from DB
		 */
	    String tag = "showpapers";
        Paper p = new Paper(-2);
        SearchCriteria search = new SearchCriteria();
        search.setPaper(p);
        ReadServiceImpl readService = new ReadServiceImpl();
        SearchResult search_result = readService.getPaper(search); 
        Paper[] result = (Paper[])search_result.getResultObj();
        if (result==null || result.length ==0)
        {
        	info.append(XMLHelper.tagged("status","" + user + ": no papers available"));
        	commit(res,tag);
        }
        else
        {
        	info.append("<content>");
        	for (int i=0;i<result.length;i++)
        	{
        		p = result[i];
        		info.append(p.toXML());
        	}
        	info.append("</content>");
        	info.append(XMLHelper.tagged("status","" + user + ": all papers"));
        	commit(res,tag);	
        }
}
	
	public void email(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		if (req.getParameter("email")!=null)
		{
			info.append("<content>");
			info.append(XMLHelper.tagged("recv",req.getParameter("email")));
			info.append("</content>");
		}
		else
		{
			info.append(XMLHelper.tagged("content",""));
		}
		info.append(XMLHelper.tagged("status","" + user + ": please write an email"));
		String tag="email";
		commit(res,tag);
	}
	
	public void send_email(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{		
		String[] formular = new String[] {req.getParameter("Recv"),
											req.getParameter("Subj"),
											  req.getParameter("Cont")};
		FormularChecker checker = new FormularChecker(formular);
		boolean VALID = checker.EmailCheck(0);
		boolean SENDED = false;
		if (VALID)
		{
		     /*
	         * FIXME get SMTP-SERVER
	         * test with your own smtp server an mail address
	         */
			SMTPClient MyE = new SMTPClient("127.0.0.1","localHost",
				formular[0],formular[1],formular[2]);
			SENDED=MyE.send();		
			session = req.getSession(true);
			String user = session.getAttribute("user").toString();
			if(SENDED)
			{
				info.append(XMLHelper.tagged("content",""));
				info.append(XMLHelper.tagged("status","" + user + ": E-Mail successfully send to " + formular[0] +" at " + MyE.getDate()));
				String tag = "email";
				commit(res,tag);
			}
		}	
//Case of Error, defining handler here; addTaged(subj),..., to fill textfields again
		if(!SENDED || !VALID)
		{
			if(VALID && !SENDED)
				info.append(XMLHelper.tagged("status","SENDING PROBLEMS : INFORM YOUR ADMIN"));
			if(!VALID)
				info.append(XMLHelper.tagged("status","ENTER A VALID EMAIL ADDRESS"));
			info.append("<content>");
			info.append(XMLHelper.tagged("subj",formular[1]));
			info.append(XMLHelper.tagged("recv",formular[0]));
			info.append(XMLHelper.tagged("cont",formular[2]));
			info.append("</content>");
			String tag = "email";
			commit(res,tag);
		}
	}
	
	private void commit(HttpServletResponse res, String tag) 
	{
	    try
	    {
		    PrintWriter out = res.getWriter();
			res.setContentType("text/html; charset=ISO-8859-1");
			helper.addXMLHead(result);
			result.append("<result>\n");
			result.append(myNavCol);
			result.append("<" + tag + ">\n");
			result.append(info.toString());
			result.append("</" +tag + ">\n");
			result.append("</result>\n");
			System.out.println(result.toString());
			String xslt = path + "/style/xsl/chair.xsl";
		    StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
			StreamSource xsltSource = new StreamSource(xslt);
			XMLHelper.process(xmlSource, xsltSource, out);
			out.flush();
	    }
	    catch(IOException e)
	    {
	        e.printStackTrace();
	    }
	}
}

