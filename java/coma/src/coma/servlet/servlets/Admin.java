
package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;
import javax.xml.transform.stream.StreamSource;
import coma.handler.impl.db.*;
import coma.entities.Person;
import coma.entities.Conference;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.servlet.util.FormularChecker;
import coma.servlet.util.Navcolumn;
import coma.servlet.util.SMTPClient;
import coma.servlet.util.SessionAttribs;
import coma.servlet.util.XMLHelper;

/**
 * @author superingo
 *
 * TODO To change the template for this generated type comment go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
public class Admin extends HttpServlet 
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
	Person user_person;
	
	
	public void doGet(HttpServletRequest req,HttpServletResponse res) 
	{
		info.delete(0,info.length());
		result.delete(0,result.length());
		String action = req.getParameter("action");
		HttpSession session= req.getSession(true);
		path = getServletContext().getRealPath("");
		user_person = (Person)session.getAttribute(SessionAttribs.PERSON);
		myNavCol = new Navcolumn(req.getSession(true));
		if (action.equals("setup"))
		{
			String tag="setup";
			info.append(XMLHelper.tagged("status","Setup new chair"));
			info.append(XMLHelper.tagged("content",""));
			commit(res,tag);
		}
		if (action.equals("add_conference"))
		{
			add_conference(req,res,session);
		}
	}
	
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
	{
		doGet(request, response);
	}		

	private void add_conference(HttpServletRequest req, HttpServletResponse res,HttpSession session)
	{
		String tag;
		String[] formular=new String[] {req.getParameter("last_name"),req.getParameter("first_name"),
				req.getParameter("email"),req.getParameter("passwd")};
		FormularChecker checker = new FormularChecker(formular);
		if(checker.check())
		{
			tag = "setup_complete";
			String email = req.getParameter("email");
			String last_name = req.getParameter("last_name");
			String first_name = req.getParameter("first_name");
			String password = req.getParameter("passwd");
			Person p = new Person(-1);
			p.setEmail(email);
			p.setPassword(password);
			p.setLast_name(last_name);
			p.setFirst_name(first_name);
			p.setRole_type(2);
			Conference c = new Conference();
			c.setName(email);
			InsertServiceImpl insert = new InsertServiceImpl();
			insert.insertConference(c);
			ReadServiceImpl read = new ReadServiceImpl();
			SearchCriteria search = new SearchCriteria();
			search.setConference(c);
			SearchResult result = read.getConference(search);
			Conference[] result_conference= (Conference[])result.getResultObj();
			int id = result_conference[0].getId();
			insert.setPersonRole(user_person.getId(),id,6,0);
			p.setConference_id(id);
			insert.insertPerson(p);
			info.append(XMLHelper.tagged("status","Setup successfull"));
			info.append(XMLHelper.tagged("status",""));
			//send_email(email);
		}
		else
		{
			tag = "setup";
			info.append(XMLHelper.tagged("status","Please fill out all fields"));
			info.append("<content>");
			info.append(XMLHelper.tagged("last_name",formular[0]));
			info.append(XMLHelper.tagged("first_name",formular[1]));
			info.append(XMLHelper.tagged("email",formular[2]));
			info.append(XMLHelper.tagged("passwd",formular[3]));
			info.append("</content>");
		}
		commit(res,tag);
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
			String xslt = path + "/style/xsl/admin.xsl";
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
	
	private void send_email(String address)
	{	
		/*
		 * 
		 */
	    boolean VALID = false; //checker.EmailCheck(0); FIXME FIXME FIXME NOBUILD 
		boolean SENDED = false;
		String[] formular= null; // FIXME FIXME FIXME NOBUILD 
		if (VALID)
		{
		     /*
	         * FIXME get SMTP-SERVER
	         * test with your own smtp server an mail address
	         */
			SMTPClient MyE = new SMTPClient("localHost",
				formular[0],formular[1],formular[2]);
			SENDED=MyE.send();		
			// FIXME FIXME FIXME NOBUILD session = req.getSession(true);
			String user = ""; // FIXME FIXME FIXME NOBUILD session.getAttribute("user").toString();
			if(SENDED)
			{
				info.append(XMLHelper.tagged("content",""));
				info.append(XMLHelper.tagged("status","" + user + ": E-Mail successfully send to " + formular[0] +" at " + MyE.getDate()));
				String tag = "email";
				//FIXME FIXME FIXME NOBUILD commit(res,tag);
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
			// FIXME FIXME FIXME NOBUILD commit(res,tag);
		}
	}
	
	
	
	
}
