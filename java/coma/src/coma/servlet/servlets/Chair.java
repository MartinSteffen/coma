package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;
import java.util.GregorianCalendar;

import javax.servlet.ServletConfig;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;
import javax.xml.transform.stream.StreamSource;

import coma.entities.Conference;
import java.util.Vector;
import coma.entities.*;
import coma.servlet.util.XMLHelper;
import coma.handler.impl.db.*;
import coma.servlet.util.*;

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
	
	public void doGet(HttpServletRequest req,HttpServletResponse res) 
	{
		info.delete(0,info.length());
		result.delete(0,result.length());
		String action = req.getParameter("action");
		HttpSession session= req.getSession(true);
		//path = "/home/superingo/jakarta-tomcat-5.0.28/webapps/coma";
		path = getServletContext().getRealPath("");
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
		if (action.equals("topic"))
		{
			topic(req,res,session);
		}
		if (action.equals("assign"))
		{
			assign(req,res,session);
		}
		if (action.equals("doAssign"))
		{
			doAssign(req,res,session);
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
			if (req.getParameter("invite as").equals("author"))
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
	
	public void topic(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		String tag;
		if(req.getParameter("topic_target").equals("add"))
		{
		    Topic[] topics_array = (Topic[])session.getAttribute("topics");	
			tag="add_topics";
			int topics = Integer.parseInt(req.getParameter("topics"));
			session.setAttribute("number_of_topics",topics);
			info.append("<content>");
			for (int i=0;i<topics_array.length;i++)
			{
				info.append(topics_array[i].toXML());
			}
			for(int i=0;i<topics;i++)
			{
				info.append("<topic_new>");
				info.append(XMLHelper.tagged("number",i));
				info.append("</topic_new>");
			}
			info.append("</content>");
			info.append(XMLHelper.tagged("status","please insert topic(s): "));
			commit(res,tag);
		}
		
		if(req.getParameter("topic_target").equals("save"))
		{
			int topics = Integer.parseInt(session.getAttribute("number_of_topics").toString());
			InsertServiceImpl insert = new InsertServiceImpl();
			Conference c = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
			session.setAttribute("number_of_topics",null);
			for (int i=0;i<topics;i++)
			{
				insert.insertTopic(c.getId(),req.getParameter(String.valueOf(i)));
			}
			setup(req,res,session);
		}
		
		if(req.getParameter("topic_target").equals("delete"))
		{
			int id=Integer.parseInt(req.getParameter("id"));
			/*
			 * TODO delete topic from DB
			 */
			setup(req,res,session);
		}
		
		/*
		 * TODO update Topic
		 */
		if(req.getParameter("topic_target").equals("update"))
		{
			int id=Integer.parseInt(req.getParameter("id"));
			Conference c = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
			UpdateServiceImpl update = new UpdateServiceImpl();
			Topic t = new Topic(id);
			t.setConferenceId(c.getId());
			//update.updateTopic(c);
			setup(req,res,session);
		}	
		
	}
	
	public void setup(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		String tag;
		ReadServiceImpl readService = new ReadServiceImpl();
	    Topic t = new Topic(-1);
	    Conference c = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
	    SearchResult search_result = readService.getTopic(t.getId(),c.getId());
	    Topic[] topics = (Topic[])search_result.getResultObj();	
		if (req.getParameter("target").equals("topics"))
		{
			tag="setup_topics";
			info.append(XMLHelper.tagged("status","topics: "));
			info.append("<content>");
			for (int i=0;i<topics.length;i++)
			{
				info.append(topics[i].toXML());
			}
			info.append("</content>");
			session.setAttribute("topics",topics);
			commit(res,tag);
		}
		
		if (req.getParameter("target").equals("conference"))
		{
			tag = "setup";
			info.append(XMLHelper.tagged("status","you are chair of: "));
			info.append("<content>");
			if (c!=null)
				info.append(c.toXML(Entity.XMLMODE.DEEP));	
			if (c.getMin_review_per_paper()==0)
				info.append(XMLHelper.tagged("min_setup",""));
			if (c.getConference_start()==null)
				info.append(XMLHelper.tagged("start_setup",""));
			if (c.getConference_end()==null)
				info.append(XMLHelper.tagged("end_setup",""));
			if(topics!=null)
				info.append(XMLHelper.tagged("topic_numbers",topics.length));
			else
				info.append(XMLHelper.tagged("topic_numbers",0));
			for (int i=0;i<topics.length;i++)
			{
				info.append(topics[i].toXML());
			}
			info.append("</content>");
			commit(res,tag);
		}
	}

	public void send_setup(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{ 
		GregorianCalendar calendar = null;
		Conference c = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
		if (req.getParameter("conference name")!=null)
			c.setName(req.getParameter("conference name"));
		if (req.getParameter("homepage")!=null)
			c.setHomepage(req.getParameter("homepage"));
		if (req.getParameter("description")!=null)
			c.setDescription(req.getParameter("description"));
		if (req.getParameter("min")!=null)
			if (!(req.getParameter("min").equals("")))
				c.setMin_review_per_paper(Integer.parseInt(req.getParameter("min")));	
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
		if (req.getParameter("start_day")!=null)
			if (!(req.getParameter("start_day").equals("")) && !(req.getParameter("start_month").equals("")) && !(req.getParameter("start_year").equals("")))
			{
			calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("start_year")),
		    		Integer.parseInt(req.getParameter("start_month"))-1,Integer.parseInt(req.getParameter("start_day")));
			    c.setConference_start(calendar.getTime());
			}
		if (req.getParameter("end_day")!=null)
			if (!(req.getParameter("end_day").equals("")) && !(req.getParameter("end_month").equals("")) && !(req.getParameter("end_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("end_year")),
			    		Integer.parseInt(req.getParameter("end_month"))-1,Integer.parseInt(req.getParameter("end_day")));
			    c.setConference_end(calendar.getTime());
			}
		if (!(req.getParameter("paper_day").equals("")) && (req.getParameter("paper_month").equals("")) && !(req.getParameter("paper_year").equals("")))
		{
			calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("paper_year")),
		    		Integer.parseInt(req.getParameter("paper_month"))-1,Integer.parseInt(req.getParameter("paper_day")));
		    c.setPaper_submission_deadline(calendar.getTime());
		}
		if (!(req.getParameter("review_day").equals("")) && !(req.getParameter("review_month").equals("")) && !(req.getParameter("review_year").equals("")))
		{
			calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("review_year")),
		    		Integer.parseInt(req.getParameter("review_month"))-1,Integer.parseInt(req.getParameter("review_day")));
		    c.setReview_deadline(calendar.getTime());
		}
		if (!(req.getParameter("not_day").equals("")) && !(req.getParameter("not_month").equals("")) && !(req.getParameter("not_year").equals("")))
		{
			calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("not_year")),
		    		Integer.parseInt(req.getParameter("not_month"))-1,Integer.parseInt(req.getParameter("not_day")));
		    c.setNotification(calendar.getTime());
		}
		UpdateServiceImpl update = new UpdateServiceImpl();
		update.updateConference(c);
		session.setAttribute(SessionAttribs.CONFERENCE,c);
		setup(req,res,session);	
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
		        Person p = new Person(-1);
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
        		info.append(p.toXML(Entity.XMLMODE.SHALLOW));
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
	
	private void assign(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{	
		Paper p =null;
		int PaperID;
		if(req.getParameter("id")!= null)
		{
			PaperID = Integer.parseInt(req.getParameter("id"));
			session.setAttribute("PaperID",req.getParameter("id"));
			p = new Paper(PaperID);
		}
		else
		{
			PaperID = Integer.parseInt(((String)session.getAttribute("PaperID")));
			p = new Paper(PaperID);
		}
        SearchCriteria search = new SearchCriteria();
        search.setPaper(p);
        ReadServiceImpl readService = new ReadServiceImpl();
        SearchResult search_resultP = readService.getPaper(search); 
        Paper[] resultP = (Paper[])search_resultP.getResultObj(); 
        ReviewReport RR = new ReviewReport();
        RR.setPaperId(PaperID);    
        search.setReviewReport(RR);
        SearchResult search_resultR = readService.getReviewReport(search); 
        ReviewReport[] resultR = (ReviewReport[])search_resultR.getResultObj();
        info.append("<content>");
        String tag = "assign";
        if (resultP==null || resultP.length ==0)
        {
        	info.append("</content>");
        	info.append(XMLHelper.tagged("status","" + user + ": no papers available"));
        	commit(res,tag);
        }
        else
        {
        	for (int i=0;i<resultP.length;i++)
        	{
        		p = resultP[i];
        		System.out.println(p.getAbstract());
        		info.append(p.toXML());
        	}
	        Person p1 = new Person(-1);
	        p1.setRole_type(2);
			SearchCriteria search1 = new SearchCriteria();
	        search.setPerson(p1);
	        ReadServiceImpl readService1 = new ReadServiceImpl();
	        SearchResult search_result1 = readService.getPerson(search);
	        Person[] result1 = (Person[])search_result1.getResultObj();  
	   	 	if (result1==null || result1.length ==0)
	        {
	   	 		info.append("</content>");
	   	 		info.append(XMLHelper.tagged("status","" + user + ": no reviewers available"));
	        	commit(res,tag);
	        }
	        else
	        {
	        	Vector CheckedReviewers = new Vector();
	          	for (int i=0;i<result1.length;i++)
	        	{    
	          		info.append("<personplus>");
	        		p1 = result1[i];	        		
	        		info.append(p1.toXML());
		        	for (int j=0;j<resultR.length;j++)
		        	{
		        		if(result1[i].getId() == resultR[j].get_reviewer_id())
		        		{        	        		
		        			info.append(XMLHelper.tagged("checked","checked"));
		        			CheckedReviewers.addElement(String.valueOf(resultR[j].get_reviewer_id()));
	        			}
		        	}        	
	        		info.append("</personplus>");
	        	}
	        	session.setAttribute("CheckedReviewers",CheckedReviewers);
	        	info.append("</content>");
	        	info.append(XMLHelper.tagged("status","" + user + ": Assignment"));
	        	commit(res,tag);
	        }
        }
 	}
        
	private void doAssign(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
	String PaperID = (String)session.getAttribute("PaperID");
	String[] NewReviewerIDs = req.getParameterValues("CB");
	Vector OldCheckedReviewers =(Vector)session.getAttribute("CheckedReviewers");
	session.setAttribute("CheckedReviewers",null);
	String tag = "assign";
	if(NewReviewerIDs != null)
	{
		for(int j=0;j<OldCheckedReviewers.size();j++)
		{
			boolean toDelete=true;
			for(int i=0;i<NewReviewerIDs.length;i++)
			{
				if (((String)OldCheckedReviewers.elementAt(j)).equals(NewReviewerIDs[i]))
				{
					NewReviewerIDs[i]=null;
					toDelete=false;
					break;				
				}	
			}
			if(toDelete)
			{
				System.out.println("TODEL :"+OldCheckedReviewers.elementAt(j).toString());
				/*
				 * TODO delete ReviewReport
				 */
			}	
		}
	    for(int i=0;i<NewReviewerIDs.length;i++)
	    {
	    	if(NewReviewerIDs[i] != null)
	    	{
	    		System.out.println("RR"+ NewReviewerIDs[i]);
	    		ReviewReport RR = new ReviewReport(-1);
	    		InsertServiceImpl insert = new InsertServiceImpl();
	    		RR.set_paper_Id(Integer.parseInt(PaperID));   
	    		RR.set_reviewer_id(Integer.parseInt(NewReviewerIDs[i]));
	    		insert.insertReviewReport(RR);
	    	}
	    }
	}
	else
	{
		for(int j=0;j<OldCheckedReviewers.size();j++)
		{
			System.out.println("TODEL :"+OldCheckedReviewers.elementAt(j).toString());
			/*
			 * TODO delete ReviewReport
			 */
		}
	}
	info.append(XMLHelper.tagged("status","" + user + ": Done successfulll: "));
	assign(req,res,session);
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

