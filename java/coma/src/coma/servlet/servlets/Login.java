package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;

import coma.servlet.util.*;
import coma.entities.*;
import coma.handler.impl.db.ReadServiceImpl;


import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;


import coma.servlet.util.XMLHelper;

/**
 * @author Peter Kauffels & Mohamed Z. Albari
 */
public class Login extends HttpServlet {
	DataSource ds = null;
	StringBuffer info = new StringBuffer();
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();
	Person myPerson = null;
	String role = new String();
	
	/*public void init(ServletConfig config) 
	 {
	 // The DB-Connection will be done in the handling-class
	  try 
	  {
	  Context initCtx = new InitialContext();
	  Context envCtx = (Context) initCtx.lookup("java:comp/env");
	  ds = (DataSource) envCtx.lookup("jdbc/coma");
	  super.init(config);
	  } 
	  catch (Exception e) 
	  {
	  helper.addError(e.getMessage().toString(), info);
	  }
	  }*/
	
	public void doGet(HttpServletRequest request, HttpServletResponse response)
	throws ServletException, java.io.IOException {
		
		HttpSession session;
		session = request.getSession(true);
		String email = request.getParameter(FormParameters.EMAIL);
		String passwd = request.getParameter(FormParameters.PASSWORD);
		
		String path = getServletContext().getRealPath("");
		String xslt = path+"/style/xsl/index.xsl";
		PrintWriter out = response.getWriter();
		
		
		helper.addXMLHead(result);
		result.append("<login>\n");
		
		try 
		{
			if ((email == null || email.equals(""))
					|| (passwd == null || passwd.equals(""))) 
			{
				session.setAttribute(SessionAttribs.PERSON,null);
				result.append(XMLHelper.tagged("form_error"));
			}
			
			
			if (validatePasswd(email, passwd)) 
			{
				
				session.setAttribute(SessionAttribs.PERSON,myPerson);
				result.append(XMLHelper.tagged("success"));
			} 
			
			else 
			{
				session.setAttribute(SessionAttribs.PERSON,null);
				result.append(XMLHelper.tagged("password_error"));
			}
			
			//Logout
			// XXX maybe adapt to coma.servlet.util.SessionAttribs. Ulrich
			//if(request.getParameter("logout") != null){
			// XXX maybe adapt to coma.servlet.util.SessionAttribs. Ulrich
			//	session.setAttribute("login", null);
			//}
			
		} 
		catch (Exception e) 
		{
			e.printStackTrace();
		} 
		finally 
		{
			info = new StringBuffer();
			result = new StringBuffer();
		}
	}
	
	
	public void doPost(HttpServletRequest request, HttpServletResponse response)
	throws ServletException,java.io.IOException{
		doGet(request, response);
	}
	
	
	
	private boolean validatePasswd(String email, String passwd) throws Exception{
	
		boolean isValid = false;
		
		ReadServiceImpl myReadService = new ReadServiceImpl();
		Person mySearchPerson = new Person(-1);
		mySearchPerson.setEmail(email);
		SearchCriteria mysc = new SearchCriteria();
		mysc.setPerson(mySearchPerson);
		SearchResult mySR = myReadService.getPerson(mysc);
		if (!mySR.isSUCCESS())
			throw new Exception(mySR.getInfo());
		else {
		} 
		myPerson =((Person[])mySR.getResultObj())[0];
		
		if (myPerson.getPassword().equals(passwd))
			isValid=true;
		return isValid;
	}
	
}
