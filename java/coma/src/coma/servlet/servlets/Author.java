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

import coma.entities.Conference;
import coma.entities.Entity;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.SearchCriteria;
import coma.entities.Topic;

import coma.entities.SearchResult;

import coma.handler.impl.db.DeleteServiceImpl;
import coma.handler.impl.db.InsertServiceImpl;
import coma.handler.impl.db.ReadServiceImpl;
import coma.handler.util.EntityCreater;

import coma.servlet.util.FormParameters;
import coma.servlet.util.Navcolumn;
import coma.servlet.util.SessionAttribs;
import coma.servlet.util.XMLHelper;
/**
 * @author mti,owu
 * @version 1.1
 * 
 * The Author servlet is responsible for all functions the auther can do.
 * 
 */
public class Author extends HttpServlet {
	
	static enum ACTIONS {NULL, SUBMITPAPER, UPDATEPAPER, PROCESSPAPER, RETRACTPAPER }; 
	
	private ReadServiceImpl myReadService = new ReadServiceImpl();
	private DeleteServiceImpl myDeleteService = new DeleteServiceImpl();
	private InsertServiceImpl myInsertService = new InsertServiceImpl();
	
	public void doGet(HttpServletRequest request, HttpServletResponse response)
	throws ServletException, java.io.IOException {
	
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();
	ACTIONS action = ACTIONS.NULL;
	SearchResult mySR =null;
	
	try {
		action = ACTIONS.valueOf(request.getParameter(FormParameters.ACTION).toUpperCase());
	} catch (RuntimeException e) {
			// TODO Auto-generated catch block
		e.printStackTrace();
	}
	
	 

//	display the parameter names and values
	Enumeration paramNames = request.getParameterNames();
	
	String parName;//this will hold the name of the parameter from the HTML form
	HttpSession session = request.getSession(true);
	
	Person theLogedPerson = (Person)session.getAttribute(SessionAttribs.PERSON);
	Conference theConf = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
	Navcolumn myNavCol = new Navcolumn(session);
	String path = getServletContext().getRealPath("");
	String xslt = path+"/style/xsl/author.xsl";
	PrintWriter out = response.getWriter();
	Topic[] topicArray = null;
	mySR = myReadService.getTopic(-1,theConf.getId());
	if (mySR != null){
		topicArray = (Topic[]) mySR.getResultObj();
	}
	
	helper.addXMLHead(result);
	
	result.append("<author>\n");
	
	switch (action) {
	case NULL: // show all his submitted papers
		result.append("<showpaper>\n");
		try {
			Paper[] thePapers = null; //= theLogedPerson.getPapers();
			Paper mySearchPaper = new Paper(-1);
			mySearchPaper.setAuthor_id(theLogedPerson.getId());
			SearchCriteria mysc = new SearchCriteria();
			mysc.setPaper(mySearchPaper);
			mySR = myReadService.getPaper(mysc);
			result.append("<success>\n");
			if (mySR != null){
				thePapers = (Paper[])mySR.getResultObj();
				result.append(XMLHelper.tagged("content",mySR.info));
				for(int i=0;i<thePapers.length; i++)
					result.append(thePapers[i].toXML());
				}
			
			
			else {
				
				result.append(XMLHelper.tagged("content","sr leer"));
			}
				
				

			} catch (Exception e) {
				result.append(XMLHelper.tagged("failed",e.toString()));
			}
			result.append("</success>\n");
		
			result.append("</showpaper>\n");
    	break;
	case SUBMITPAPER: // submit form for a paper
		result.append("<submitpaper>");
		
		for (int i = 0; i < topicArray.length; i++) {
				result.append(topicArray[i].toXML());
		}
		
		result.append("</submitpaper>");
		
		break;
	case UPDATEPAPER: // make a update to an previous submitted paper
		try{
				int paper_id = Integer.parseInt(request.getParameter(FormParameters.PAPER_ID));
				Paper theOldPaper = new Paper(paper_id) ;
				SearchCriteria mysc = new SearchCriteria();
				mysc.setPaper(theOldPaper);
				mySR = myReadService.getPaper(mysc);
				if (mySR != null){
					Paper[] thePapers = (Paper[])mySR.getResultObj();
					if (thePapers.length==1) theOldPaper=thePapers[0];
				}
				result.append("<submitpaper>\n");
				result.append(theOldPaper.toXML());
				for (int i = 0; i < topicArray.length; i++) {
					result.append(topicArray[i].toXML());
				}
				result.append("</submitpaper>\n");
			} catch (NumberFormatException e1) {
				// TODO Auto-generated catch block
				result.append(XMLHelper.tagged("error",e1.toString()));
			}
		break;
    case PROCESSPAPER: // process submitted paper
    	EntityCreater myCreater = new EntityCreater();
		
		try {
			Paper theNewPaper = myCreater.getPaper(request);
			
			result.append(XMLHelper.tagged("writefile",""));
			session.setAttribute(SessionAttribs.PAPER,theNewPaper);
		} catch (Exception e) {
			result.append("<failed>\n");
			String[] checkboxes = request.getParameterValues(FormParameters.TOPICS);
			for (int i = 0; i < checkboxes.length; i++) {
				 result.append(XMLHelper.tagged("info",checkboxes[i]));
			} 
			
			result.append("</failed>");
			result.append("<submitpaper>\n");
			result.append(XMLHelper.tagged("error",e.toString()));
			while(paramNames.hasMoreElements()){
			    parName = (String) paramNames.nextElement();
			    result.append(XMLHelper.tagged(parName,request.getParameter(parName).trim()));  
			}//while
			mySR = myReadService.getTopic(-1,theConf.getId());
			result.append(XMLHelper.tagged("info",mySR.getInfo()));
			for (int i = 0; i < topicArray.length; i++) {
					result.append(topicArray[i].toXML());
			}
				    
			result.append("</submitpaper>\n");
			
		}
    	break;
	default:
		
		break;
	}
	
	result.append(myNavCol.toString());
	result.append("</author>\n");
	response.setContentType("text/html; charset=ISO-8859-1");
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
