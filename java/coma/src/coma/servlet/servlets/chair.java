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
import coma.servlet.util.*;
import coma.entities.*;
import coma.handler.impl.db.InsertServiceImpl;
//import coma.handler.db.*;

import java.util.Date;
//import coma.entities.*;

//import org.apache.catalina.realm.RealmBase;

import coma.servlet.util.XMLHelper;

/**
 * @author Peter Kauffels & Harm Brandt
 * @version 1.1
 * The chair servlet is responsible for all functions the chair can do.
   Changes at 12.12: I added commit Method(at the bottom).
   	by hbra@inf...	    added EMail function(not yet for invite)
   						changes path settings
   						added if ( action == null) at 67
 * Changes at 14.12: change method commit, now runs with parameters 
 * HttpServletResponse res and String tag.  
 * delete redundant code
 * 
 **/

public class chair extends HttpServlet 
{
	DataSource ds = null;
	StringBuffer info = new StringBuffer();
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();
	StringBuffer status = new StringBuffer();
	String path = null;
	String user = null;

	public void init(ServletConfig config) 
	{
		try {
		//	Context initCtx = new InitialContext();
		//	Context envCtx = (Context) initCtx.lookup("java:comp/env");
		//	ds = (DataSource) envCtx.lookup("jdbc/coma");
			super.init(config);
		} catch (Exception e) {
	//		helper.addInfo(e.getMessage().toString(), info);
		}
	}
	
	public void doGet(HttpServletRequest req,HttpServletResponse res) 
	{
		info.delete(0,info.length());
		result.delete(0,result.length());
		String action = req.getParameter("action");
		HttpSession session= req.getSession(true);;
		path = getServletContext().getRealPath("");
		user = session.getAttribute("user").toString();
		if (action.equals("login"))
		{
			login(req,res,session);
		}
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
	
	public void login(HttpServletRequest req,HttpServletResponse res,HttpSession session)
		{
			helper.addTagged("content","Welcome to Coma, " + user + "\n", info);
			helper.addTagged("status","Welcome chair\n ",info);
			String tag = "login";
			commit(res,tag); 
		}
		
	public void invite_person(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		helper.addTagged("content","",info);
		helper.addTagged("status","" + user + ": invite a person\n",info);
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
		    password_maker password_maker = new password_maker(req.getParameter("first name"),req.getParameter("last name"),req.getParameter("email"));
			String pass = password_maker.generate_password();
			Person p = new Person(-1);
			p.setPassword(pass);
			p.setFirst_name(req.getParameter("first name"));
			p.setLast_name(req.getParameter("last name"));
			p.setEmail(req.getParameter("email"));
			InsertServiceImpl insert = new InsertServiceImpl();
			insert.insertPerson(p);
		    helper.addTagged("status","" + user + ": E-Mail successfully send to " + req.getParameter("first name") +" " +  req.getParameter("last name"),info);
			helper.addTagged("content","",info);
		    String tag = "invitation_send";
		    commit(res,tag);
		}
		else 
		{
		    info.delete(0,info.length());
			result.delete(0,result.length());
			String form = "<first>" + req.getParameter("first name") + "</first>";
			helper.addTagged("status","" + user + ": you have to fill out all *-fields",info);
			helper.addTagged("content",form,info);
			String tag = "invite";
			commit(res,tag);
		}
	}
	
	public void setup(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		helper.addTagged("status","" + user + ": Setup the conference",info);
		helper.addTagged("content","",info);
		helper.addTagged("setup",info.toString(),info);
		String tag = "setup";
		commit(res,tag);
	}

	public void send_setup(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
	    /*
	     * FIXME get Conference from Database
	     */
	    /*Conference c = new Conference(-1);
	    ReadServiceImpl readService = new ReadServiceImpl();
	    SearchCriteria search = new SearchCriteria();
	    search.setConference(c);
	    SearchResult search_result = readService.getConference();
	    if (!search_result.isSuccess())
	    {*/
	    Date d = null;
	    String tag = "setup";
		String[] formular = new String[] {req.getParameter("conference name"),req.getParameter("homepage"),
		req.getParameter("min_reviewers"),req.getParameter("start_day"),req.getParameter("start_month"),
		req.getParameter("start_year"),req.getParameter("end_day"),
		req.getParameter("end_month"),req.getParameter("end_year"),
		req.getParameter("abstract"),req.getParameter("final"),req.getParameter("paper"),
		req.getParameter("review")};
	    FormularChecker checker = new FormularChecker(formular);
		if (checker.check())
		{
		    /*
		     * FIXME Insert Conference in Database, problem with Date
		     */
		    /*Conference c = new Conference(-1);
		    c.setName(req.getParameter("conference name"));
		    c.setHomepage(req.getParameter("Homepage"));
		    c.setDescription(req.getParameter("description"));
		    d = new Date();
		    d.setDate(Integer.parseInt(req.getParameter("start_day")));
		    d.setMonth(Integer.parseInt(req.getParameter("start_month")));
		    d.setYear(Integer.parseInt(req.getParameter("start_year")));
		    c.setConference_start(d);
		    d = new Date();
		    d.setTime()
		    d.setDate(Integer.parseInt(req.getParameter("end_day")));
		    d.setMonth(Integer.parseInt(req.getParameter("end_month")));
		    d.setYear(Integer.parseInt(req.getParameter("end_year")));
		    c.setConference_end(d);
		    InsertServiceImpl insert = new InsertServiceImpl();
		    insert.insertConference(c);*/
			helper.addTagged("status","" + user + ": Congratulations you have setup a new conference",info);
			helper.addTagged("content","",info);
			commit(res,tag);
		}
		else
		{
		    helper.addTagged("status","" + user + ": please fill out all *-fields",info);
			helper.addTagged("content","",info);
			commit(res,tag);
		}
	    //}
	}
	
	public void show_authors(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
        /*
         * FIXME get all authors from db
         */
        /*Person p = new Person(-1);
        SearchCriteria search = new SearchCriteria();
        search.setPerson(p);
        ReadServiceImpl readService = new ReadServiceImpl();
        SearchResult search_result = readService.getPerson(search);
        search_result.getResultObj();*/
		helper.addTagged("content","",info);
		helper.addTagged("status","" + user + ": all authors",info);
		String tag = "showauthors";
		commit(res,tag);
	}

	public void show_reviewers(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
        /*
         * FIXME get all reviewers
         */
        /*Person p = new Person(-1);
        SearchCriteria search = new SearchCriteria();
        search.setPerson(p);
        ReadServiceImpl readService = new ReadServiceImpl();
        SearchResult search_result = readService.getPerson(search);
        search_result.getResultObj();*/
		helper.addTagged("content","",info);
		helper.addTagged("status","" + user + ": list of all reviewers",info);
		String tag = "showreviewers";
		commit(res,tag);
	}
	
	public void show_papers(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
        /*
         * FIXME get all reviewers
         */
        /*Paper p = new Paper(-1);
        SearchCriteria search = new SearchCriteria();
        search.setPaper(p);
        ReadServiceImpl readService = new ReadServiceImpl();
        SearchResult search_result = readService.getPaper(search);
        search_result.getResultObj();*/
		helper.addTagged("content","",info);
		helper.addTagged("status","" + user + ": all papers",info);
		String tag = "showpapers";
		commit(res,tag);	
}
	
	public void email(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
	        helper.addTagged("content","",info);
			helper.addTagged("status","" + user + ": please write an email",info);
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
				helper.addTagged("content","",info);
				helper.addTagged("status","" + user + ": E-Mail successfully send to " + formular[0] +" at " + MyE.getDate(),info);
				String tag = "email";
				commit(res,tag);
			}
		}	
//Case of Error, defining handler here; addTaged(subj),..., to fill textfields again
		if(!SENDED || !VALID)
		{
			if(VALID && !SENDED)
				helper.addTagged("status","SENDING PROBLEMS : INFORM YOUR ADMIN",info);
			if(!VALID)
				helper.addTagged("status","ENTER A VALID EMAIL ADDRESS",info);
			helper.addTagged("subj",formular[1],info);
			helper.addTagged("recv",formular[0],info);
			helper.addTagged("cont",formular[2],info);
			helper.addTagged("content","",info);
			String tag = "email";
			commit(res,tag);
		}
	}
	
	/** commit method to avoid redundant(falls das in Engl so hei�t) code*/
	private void commit(HttpServletResponse res, String tag) 
	{
	    try
	    {
		    PrintWriter out = res.getWriter();
			res.setContentType("text/html; charset=ISO-8859-1");
			helper.addXMLHead(result);
			result.append("<result>\n");
			result.append("<" + tag + ">\n");
			result.append(info.toString());
			result.append("</" +tag + ">\n");
			result.append("</result>\n");
			String xslt = path + "/style/xsl/chair.xsl";
		    StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
			StreamSource xsltSource = new StreamSource(xslt);
			XMLHelper.process(xmlSource, xsltSource, out);
			out.flush();
			System.out.println(result);
	    }
	    catch(IOException e)
	    {
	        e.printStackTrace();
	    }
	}
}

