/*
 * Created on 07.12.2004
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
import javax.xml.transform.stream.StreamSource;

import coma.entities.Person;
import coma.entities.SearchResult;
import coma.handler.impl.db.InsertServiceImpl;
import coma.handler.util.EntityCreater;
import coma.servlet.util.XMLHelper;

/**
 * @author mti
 * @version 0.1
 * 
 * Sverlet to subscribe for a conference as a participant 
 * 
 */
public class Subscribe  extends HttpServlet {
	
	
	
	public void doGet(HttpServletRequest request, HttpServletResponse response)
    	throws ServletException, java.io.IOException {
		
		StringBuffer result = new StringBuffer();
		XMLHelper helper = new XMLHelper();
		
		
//		display the parameter names and values
		Enumeration paramNames = request.getParameterNames();
		
		String parName;//this will hold the name of the parameter from the HTML form

		boolean emptyEnum = false;
		if (! paramNames.hasMoreElements())
		    emptyEnum = true;
		
		PrintWriter out = response.getWriter();
		
		
		helper.addXMLHead(result);
		result.append("<subscribe>\n");
		result.append(new coma.servlet.util.Navcolumn(request));
		if (emptyEnum){
				
			result.append(XMLHelper.tagged("form",""));
			
			
		} else {
			EntityCreater myCreater = new EntityCreater();
			
			try {
				Person mynewPerson = myCreater.getPerson(request);
				InsertServiceImpl myInsertservice = new InsertServiceImpl();
				SearchResult sr = myInsertservice.insertPerson(mynewPerson);
				
				result.append(XMLHelper.tagged("success",sr.getInfo()));
				result.append(mynewPerson.toXML());
			} catch (IllegalArgumentException e) {
				result.append(XMLHelper.tagged("failed",""));
				result.append("<form>\n");
				while(paramNames.hasMoreElements()){
				    parName = (String) paramNames.nextElement();
				    result.append(XMLHelper.tagged(parName,request.getParameter(parName).trim()));  
				}//while
					   
				result.append("</form>\n");
			}
			
			
		}
		
		result.append("</subscribe>\n");
		response.setContentType("text/html; charset=ISO-8859-1");
		StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
		XMLHelper.process(xmlSource, coma.servlet.util.XSLT.file(this, "subscribe"), out);
		out.flush();
		
		
	
	
	}
	
	public void doPost(HttpServletRequest request, HttpServletResponse response)
    	throws ServletException,java.io.IOException{
		doGet(request, response);
	}
}
