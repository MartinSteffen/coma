package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;

import javax.servlet.ServletConfig;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;
import javax.xml.transform.stream.StreamSource;

import coma.servlet.util.XMLHelper;

/**
 * @author Peter Kauffels
 * @version 1.0
 */
public class invite extends HttpServlet {

	DataSource ds = null;
	StringBuffer info = new StringBuffer();
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();

	public void init(ServletConfig config) 
	{
		/*try {
			Context initCtx = new InitialContext();
			Context envCtx = (Context) initCtx.lookup("java:comp/env");
			ds = (DataSource) envCtx.lookup("jdbc/coma");
			super.init(config);
		} catch (Exception e) {
			helper.addInfo(e.getMessage().toString(), info);
		}*/
	}
	
	public void doGet(HttpServletRequest request,HttpServletResponse response) 
	{
		HttpSession session;
		session = request.getSession(true);
		try 
		{
			String user = session.getAttribute("user").toString();
			helper.addContent("here you can invite a person",info);
			helper.addStatus("Welcome chair " + user,info);
			//String path = getServletContext().getRealPath("/WEB-INF/");
			String xslt = "jakarta-tomcat-5.0.28/webapps/coma/style/xsl/chair.xsl";
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
}
