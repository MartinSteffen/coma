/*
 * Created on 10.12.2004
 *
 */
package coma.servlet.servlets;

import java.io.PrintWriter;
import java.io.StringReader;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.xml.transform.stream.StreamSource;

import coma.servlet.util.*;

/**
 * @author mti
 *
 * First servlet invoked by the browser
 * No function, maybe later 
 *
 */
public class Index  extends HttpServlet {
	
	
	
	
	public void doGet(HttpServletRequest request, HttpServletResponse response)
    	throws ServletException, java.io.IOException {
		
		StringBuffer result = new StringBuffer();	
		XMLHelper helper = new XMLHelper();
		Navcolumn myNavCol = new Navcolumn(request.getSession(true));
		String path = getServletContext().getRealPath("");
		String xslt = path+"/style/xsl/index.xsl";
		PrintWriter out = response.getWriter();
		helper.addXMLHead(result);
		result.append(myNavCol);
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
