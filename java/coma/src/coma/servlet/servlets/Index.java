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

import coma.entities.Conference;
import coma.entities.Person;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.handler.impl.db.ReadServiceImpl;
import coma.servlet.util.Navcolumn;
import coma.servlet.util.XMLHelper;

/**
 * @author mti
 * 
 * First servlet invoked by the browser No function, maybe later
 * 
 */
public class Index extends HttpServlet {

	public void doGet(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, java.io.IOException {

		StringBuffer result = new StringBuffer();
		XMLHelper helper = new XMLHelper();
		Navcolumn myNavCol = new Navcolumn(request.getSession(true));
		String path = getServletContext().getRealPath("");
		String xslt = path + "/style/xsl/index.xsl";
		PrintWriter out = response.getWriter();
		helper.addXMLHead(result);
		
		result.append(XMLHelper.tagged("dummy",""));
		result.append(myNavCol.toString());
		/*ReadServiceImpl myReadService = new ReadServiceImpl();
		Conference mySearchconference = new Conference(-1);
		SearchCriteria mysc = new SearchCriteria();
		mysc.setConference(mySearchconference);
		SearchResult mySR = myReadService.getConference(mysc);
		if (mySR != null){
			Conference[] confernceArray = (Conference[]) mySR.getResultObj();
			result.append("<conference_list>\n");
			for (int i = 0; i < confernceArray.length; i++) {
				result.append(confernceArray[i].toXML());
			}
			result.append("</conference_list>\n");
			String info = mySR.getInfo();
			System.out.println(info);
		}*/
			
			
		
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
}
