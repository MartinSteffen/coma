package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;
//import java.sql.Connection;
//import java.sql.ResultSet;
//import java.sql.SQLException;
//import java.sql.Statement;

import coma.entities.*;
import javax.naming.Context;
import javax.naming.InitialContext;
import javax.servlet.ServletConfig;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;
import javax.xml.transform.stream.StreamSource;

//import org.apache.catalina.realm.RealmBase;

import coma.servlet.util.XMLHelper;

/**
 * @author Peter Kauffels & Mohamed Z. Albari
 */
public class Login extends HttpServlet {

	DataSource ds = null;
	StringBuffer info = new StringBuffer();
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();
	Person person = null;
	String role = new String();

	public void init(ServletConfig config) 
	{
	    // The DB-Connection will be done in the handling-class
//		try 
//		{
//			Context initCtx = new InitialContext();
//			Context envCtx = (Context) initCtx.lookup("java:comp/env");
//			ds = (DataSource) envCtx.lookup("jdbc/coma");
//			super.init(config);
//		} 
//		catch (Exception e) 
//		{
//			helper.addError(e.getMessage().toString(), info);
//		}
	}
	
	public void doGet(HttpServletRequest request,HttpServletResponse response) 
	{
		HttpSession session;
		session = request.getSession(true);
		String user = request.getParameter("userID");
		String passwd = request.getParameter("passwd");
		try 
		{
			if ((user == null || user.equals(""))
				|| (passwd == null || passwd.equals(""))) 
			{
			// XXX maybe adapt to coma.servlet.util.SessionAttribs. Ulrich
				session.setAttribute("login", null);
				helper.addContent("Please give me more\n",info);
			}
			
			// forward to chair, only a test
			if (validatePasswd(user, passwd)) 
			{
			    // XXX maybe adapt to coma.servlet.util.SessionAttribs. Ulrich
				session.setAttribute("person",person);
				session.setAttribute("user", new String(user));
				session.setAttribute("login", new String("ok"));
				response.sendRedirect("/coma/" + role);
			} 
			
			else 
			{
			// XXX maybe adapt to coma.servlet.util.SessionAttribs. Ulrich
			        session.setAttribute("login", null);
				helper.addContent("Wrong password, please try again\n", info);
				helper.addStatus("login not correct",info);
			}
			
			//Logout
			// XXX maybe adapt to coma.servlet.util.SessionAttribs. Ulrich
			if(request.getParameter("logout") != null){
			    // XXX maybe adapt to coma.servlet.util.SessionAttribs. Ulrich
				session.setAttribute("login", null);
			}
			/*FIXME: Path xslt */
			//String path = getServletContext().getRealPath("");
			String xslt = "jakarta-tomcat-5.0.28/webapps/coma/style/xsl/login.xsl";
			PrintWriter out = response.getWriter();
			result.append("<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>\n");
			result.append("<result>\n");
			result.append(info.toString());
			result.append("</result>\n");
			response.setContentType("text/html; charset=ISO-8859-1");
			StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
			StreamSource xsltSource = new StreamSource(xslt);
			XMLHelper.process(xmlSource, xsltSource, out);
			out.flush();
		} 
		catch (IOException e) 
		{
			e.printStackTrace();
		} 
		finally 
		{
			info = new StringBuffer();
			result = new StringBuffer();
		}
	}

	public void doPost(HttpServletRequest request,HttpServletResponse response) 
	{
		doGet(request, response);
	}
	
	/*FIXME: DB connection */
	private boolean validatePasswd(String user, String passwd) 
	{
		boolean isValid = false;
		if ((user.equals("chair")) && (passwd.equals("test")))
		{
			isValid = true;
			person = new Person(1);
			role = "chair";
		}
		/*try {
			Connection conn = ds.getConnection();
			String QUERY =
				"SELECT Passwort FROM users WHERE login = '" + user + "'";
			Statement stmt = conn.createStatement();
			ResultSet resSet = stmt.executeQuery(QUERY);
			//if (resSet.next()) {
				//String code = resSet.getString("Passwort");
				//if (RealmBase.Digest(passwd, "SHA").equals(code)) {
					//isValid = true;
				//}
			//}
			resSet.close();
			resSet = null;
			stmt.close();
			stmt = null;
			if (conn != null) {
				conn.close();
			}
		} catch (SQLException e) {
			helper.addInfo(e.getMessage(), info);
			e.printStackTrace();
		}*/
		return isValid;
	}

}
