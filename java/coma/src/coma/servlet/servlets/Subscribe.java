/*
 * Created on 07.12.2004
 *
 */
package coma.servlet.servlets;

import java.io.PrintWriter;
import java.io.StringReader;
import java.util.*;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.xml.transform.stream.StreamSource;

import coma.entities.*;
import coma.handler.impl.db.*;
import coma.handler.util.EntityCreater;
import coma.servlet.util.XMLHelper;

import coma.entities.Entity.XMLMODE;
import coma.servlet.util.*;

/**
 * @author mti, ums
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
				
		    SearchCriteria theSC = new SearchCriteria();
		    theSC.setConference(new Conference(-1));
		    Conference[] allConfs 
			= (Conference[])new coma.handler.impl.db.ReadServiceImpl()
			.getConference(theSC).getResultObj();
		    
		    result.append(XMLHelper.tagged("form", 
						   Conference.manyToXML(Arrays.asList(allConfs), XMLMODE.SHALLOW)));
		    
			
		} else {
			EntityCreater myCreater = new EntityCreater();
			
			try {
				Person mynewPerson = myCreater.getPerson(request);

				int confid 
				    = Integer.parseInt(request.getParameter(FormParameters.CONFERENCE_ID));

				boolean willBeAuthor
				    = (request.getParameter(FormParameters.WILLBEAUTHOR)!=null);

				InsertServiceImpl myInsertservice = new InsertServiceImpl();
				SearchResult sr = myInsertservice.insertPerson(mynewPerson);

				
				// now, refresh.
				SearchCriteria theSC = new SearchCriteria();
				theSC.setPerson(mynewPerson);
				// XXX no error handling.
				mynewPerson = ((Person[])new coma.handler.impl.db.ReadServiceImpl()
					       .getPerson(theSC).getResultObj())[0];

				if (mynewPerson.getId() > 0){

				myInsertservice.setPersonRole(mynewPerson.getId(), 
							      confid, 
							      willBeAuthor? User.AUTHOR 
							                  : User.PARTICIPANT,
							      User.ACTIVE);
				} else {
				    throw new ServletException("updaterefresh didn't: "+mynewPerson.getId());
				}
				result.append(XMLHelper.tagged("success",sr.getInfo()));
				result.append(mynewPerson.toXML());
				result.append(XMLHelper.tagged("confid", confid));
			} catch (IllegalArgumentException e) {
				result.append(XMLHelper.tagged("failed", e));
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
