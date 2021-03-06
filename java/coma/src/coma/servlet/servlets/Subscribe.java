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

import coma.util.logging.ALogger;
import static coma.util.logging.Severity.*;

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
					   Conference.manyToXML(Arrays.asList(allConfs), 
								XMLMODE.SHALLOW)));
		    
			
	} else {
	    EntityCreater myCreater = new EntityCreater();
			
	    try {
		Person mynewPerson = myCreater.getPerson(request);

		Person oldPerson = new Person(-1);
		oldPerson.setEmail(mynewPerson.getEmail());
		SearchCriteria sc = new SearchCriteria();
		sc.setPerson(oldPerson);

		int oldId = -1;
		try {
		    oldId= ((Person[])new coma.handler.impl.db.ReadServiceImpl()
			    .getPerson(sc).getResultObj())[0].getId();
		} catch (Exception exc){;}

		int confid 
		    = Integer.parseInt(request.getParameter(FormParameters.CONFERENCE_ID));

		boolean willBeAuthor
		    = (request.getParameter(FormParameters.WILLBEAUTHOR)!=null);

		mynewPerson.setConference_id(confid);

		mynewPerson.setRole_type(new int[]{willBeAuthor? User.AUTHOR
						   : User.PARTICIPANT});
		
		SearchResult sr;
		InsertServiceImpl myInsertservice = new InsertServiceImpl();
		if (oldId == -1){
		    sr = myInsertservice.insertPerson(mynewPerson);
		} else {
		    sr = myInsertservice.setPersonRole(oldId, confid, 
						       willBeAuthor? User.AUTHOR
						                   : User.PARTICIPANT,
						       0);
		}

		ALogger.log.log(DEBUG, sr.getInfo());

		if (sr.isSUCCESS()){
		    result.append(XMLHelper.tagged("success",sr.getInfo()));
		} else {
		    result.append(XMLHelper.tagged("failed", sr.getInfo()));
		}
		result.append(mynewPerson.toXML());
		result.append(XMLHelper.tagged("confid", confid));
	    } catch (IllegalArgumentException e) {
		result.append(XMLHelper.tagged("failed", XMLHelper.tagged(e.getMessage())));
		/*
		result.append("<form>\n");
		while(paramNames.hasMoreElements()){
		    parName = (String) paramNames.nextElement();
		    result.append(XMLHelper.tagged(parName,request.getParameter(parName).trim()));  
		}//while
					   
		result.append("</form>\n");
		*/
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
