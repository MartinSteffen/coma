/*
 * Created on 10.12.2004
 *
 */
package coma.servlet.servlets;

import java.io.File;
import java.io.PrintWriter;
import java.io.RandomAccessFile;
import java.io.StringReader;
import java.io.IOException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.xml.transform.stream.StreamSource;

import com.sun.org.apache.xerces.internal.dom.DeepNodeListImpl;

import coma.entities.Conference;
import coma.entities.Person;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.handler.impl.db.ReadServiceImpl;
import coma.servlet.util.Navcolumn;
import coma.servlet.util.XMLHelper;

import coma.entities.Entity.XMLMODE;
import coma.entities.Entity;

/**
 * @author mti
 * 
 * First servlet invoked by the browser No function, maybe later
 * 
 */
public class Index extends HttpServlet {
	
	private Navcolumn myNavCol;
	private String path;
	private StringBuffer result;
	
	public void doGet(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, java.io.IOException {

		result = new StringBuffer();
		XMLHelper helper = new XMLHelper();
		myNavCol = new Navcolumn(request.getSession(true));
		path = getServletContext().getRealPath("");
		String xslt = path + "/style/xsl/index.xsl";
		PrintWriter out = response.getWriter();
		helper.addXMLHead(result);
		
		//result.append(XMLHelper.tagged("dummy",""));
		result.append("<content>");
		result.append(myNavCol.toString());
		ShowProgram(request);
		result.append("</content>");	
		System.out.println(result.toString());
			response.setContentType("text/html; charset=ISO-8859-15");
			StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
			StreamSource xsltSource = new StreamSource(xslt);
			XMLHelper.process(xmlSource, xsltSource, out);
			out.flush();

	}

	public void doPost(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, java.io.IOException {
		doGet(request, response);
	}
	
	
	private void ShowProgram(HttpServletRequest request){
		
		if(request.getParameter("name")== null){
			Conference[] conferenceArray = myNavCol.getConferenceArray();
			if (conferenceArray != null)
				for(int i=0;i<conferenceArray.length;i++){
					File Prog = new File(path + "/papers","Program-"+conferenceArray[i].getName()+".xml");
						if(Prog.exists()){
							result.append("<conference_end>");
							result.append(conferenceArray[i].getName());
							result.append("</conference_end>");
						}
				}
		}
			else{
				try{
				File Prog = new File(path + "/papers","Program-"+request.getParameter("name")+".xml");
				RandomAccessFile input = new RandomAccessFile( Prog, "rw" );
				input.readLine();
				String tmp;
				result.append("<show_program>");
				while((tmp = input.readLine()) != null)
				result.append(tmp);
				result.append("</show_program>");
					}
					catch(IOException E){
							E.printStackTrace();
					}
				}
	}
	
}