/*
 * Created on 12.12.2004
 *
 */
package coma.servlet.servlets;


import java.io.PrintWriter;
import java.io.StringReader;
import java.util.Enumeration;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.xml.transform.stream.StreamSource;

import com.oreilly.servlet.MultipartRequest;

import coma.entities.Paper;
import coma.entities.Person;
import coma.handler.impl.db.InsertServiceImpl;
import coma.handler.util.EntityCreater;
import coma.servlet.util.SessionAttribs;
import coma.servlet.util.XMLHelper;
/**
 * @author mti
 * @version 0.1
 * 
 * The Author servlet is responsible for all functions the auther can do.
 * 
 */
public class Author extends HttpServlet {
	
	static enum ACTIONS {NULL, SUBMITPAPER, UPDATEPAPER, PROCESSPAPER, RETRACTPAPER }; 
	
	public void doGet(HttpServletRequest request, HttpServletResponse response)
	throws ServletException, java.io.IOException {
	
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();
	ACTIONS action = ACTIONS.NULL;
	
	
	try {
		action = ACTIONS.valueOf(request.getParameter("action").toUpperCase());
	} catch (RuntimeException e) {
			// TODO Auto-generated catch block
		e.printStackTrace();
	}
	
	 

//	display the parameter names and values
	Enumeration paramNames = request.getParameterNames();
	
	String parName;//this will hold the name of the parameter from the HTML form
	HttpSession session = request.getSession(true);
	
	Person theLogedPerson = (Person)session.getAttribute(SessionAttribs.PERSON);
	
	String path = getServletContext().getRealPath("");
	String xslt = path+"/style/xsl/author.xsl";
	PrintWriter out = response.getWriter();
	
	
	helper.addXMLHead(result);
	result.append("<author>\n");
	
	switch (action) {
	case NULL: // show all his submitted papers
		/*result.append("<showpaper>\n");
		try {
				Paper[] thePapers = theLogedPerson.getPapers();
				result.append(XMLHelper.tagged("success",""));
				for(int i=0;i<thePapers.length; i++)
					result.append(thePapers[i].toXML());
			} catch (Exception e) {
				result.append(XMLHelper.tagged("failed",e.toString()));
			}
		
			result.append("</showpaper>\n");*/
    	break;
	case SUBMITPAPER: // submit form for a paper
		result.append(XMLHelper.tagged("submitpaper",""));
		break;
    case PROCESSPAPER: // process submitted paper
    	EntityCreater myCreater = new EntityCreater();
		
		try {
			Paper theNewPaper = myCreater.getPaper(request);
			
			result.append(XMLHelper.tagged("writefile",""));
			session.setAttribute(SessionAttribs.PAPER,theNewPaper);
		} catch (Exception e) {
			result.append(XMLHelper.tagged("failed",""));
			result.append("<submitpaper>\n");
			result.append(XMLHelper.tagged("error",e.toString()));
			while(paramNames.hasMoreElements()){
			    parName = (String) paramNames.nextElement();
			    result.append(XMLHelper.tagged(parName,request.getParameter(parName).trim()));  
			}//while
				    
			result.append("</submitpaper>\n");
			
		}
    	break;
	default:
		
		break;
	}
	
	
	result.append("</author>\n");
	response.setContentType("text/html; charset=ISO-8859-15");
	StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
	StreamSource xsltSource = new StreamSource(xslt);
	XMLHelper.process(xmlSource, xsltSource, out);
	out.flush();
	
	


}

public void doPost(HttpServletRequest request, HttpServletResponse response)
	throws ServletException,java.io.IOException{
	doGet(request, response);
}

}
