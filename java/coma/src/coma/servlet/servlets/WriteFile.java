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
import com.oreilly.servlet.multipart.DefaultFileRenamePolicy;

import coma.entities.Paper;
import coma.handler.impl.db.InsertServiceImpl;
import coma.servlet.util.SessionAttribs;
import coma.servlet.util.XMLHelper;

/**
 * @author mti
 * @version 0.1
 * 
 * process a form with enctype="multipart/form-data" attribute
 * to store a file on the disk
 * 
 */
public class WriteFile extends HttpServlet {
	
	
	
	public void doGet(HttpServletRequest request, HttpServletResponse response)
	throws ServletException, java.io.IOException {
	
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();
	
	
	
	helper.addXMLHead(result);
	result.append("<author>\n");
	String path = getServletContext().getRealPath("");
	String xslt = path+"/style/xsl/author.xsl";
	PrintWriter out = response.getWriter();
	try {
		
		
		
		MultipartRequest mpr = new MultipartRequest(
				request,
				path+"/papers",
				(5*1024*1024),
				new DefaultFileRenamePolicy());
		
		Enumeration fileNames = mpr.getFileNames();
		
		String theSystemFileName =mpr.getFilesystemName((String) fileNames.nextElement());
		
		// get session attributes
		HttpSession session= request.getSession(true);
		
		Paper theNewPaper = (Paper) session.getAttribute(SessionAttribs.PAPER);
		theNewPaper.setFilename(theSystemFileName);
		
		
		
		
		
		
		
		InsertServiceImpl myInsertservice = new InsertServiceImpl();
		//myInsertservice.insertPaper(theNewPaper);
		result.append(XMLHelper.tagged("success",""));
		result.append(theNewPaper.toXML());
	} catch (Exception e) {
		result.append(XMLHelper.tagged("failed",""));
		result.append(XMLHelper.tagged("error",e.toString()));
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