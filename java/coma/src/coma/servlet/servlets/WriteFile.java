/*
 * Created on 12.12.2004
 *
 */
package coma.servlet.servlets;

import java.io.File;
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
import coma.entities.SearchResult;
import coma.handler.impl.db.InsertServiceImpl;
import coma.handler.impl.db.UpdateServiceImpl;
import coma.servlet.util.Navcolumn;
import coma.servlet.util.SessionAttribs;
import coma.servlet.util.XMLHelper;

/**
 * @author mti,owu
 * @version 0.9
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
		String xslt = path + "/style/xsl/author.xsl";
		PrintWriter out = response.getWriter();
		HttpSession session = request.getSession(true);
		Navcolumn myNavCol = new Navcolumn(session);
		Paper theNewPaper = (Paper) session.getAttribute(SessionAttribs.PAPER);
		if (theNewPaper.getId() != -1) { //update paper: rename the old, so a new one can be created
			File renameFile = new File(path + "/papers", theNewPaper
					.getFilename());
			if (renameFile.exists()){
			File backupFile = new File(path + "/papers/", theNewPaper
					.getFilename()
					+ theNewPaper.getAuthor_id()
					+ theNewPaper.getVersion());
			
			if(!renameFile.renameTo(backupFile))
				result.append(XMLHelper.tagged("error", "old version not corectly renamed"));
			}
			else result.append(XMLHelper.tagged("error", "old file not found Path: "+path + "/papers/" + theNewPaper
					.getFilename()));
		}
		try {

			MultipartRequest mpr = new MultipartRequest(request, path
					+ "/papers", (5 * 1024 * 1024),
					new DefaultFileRenamePolicy());

			Enumeration fileNames = mpr.getFileNames();
			String theNewFile = (String) fileNames.nextElement();
			String theSystemFileName = mpr.getFilesystemName(theNewFile);

			// get session attributes

			theNewPaper.setFilename(theSystemFileName);
			theNewPaper.setMim_type(mpr.getContentType(theNewFile));

			if (theNewPaper.getId() == -1) {
				InsertServiceImpl myInsertservice = new InsertServiceImpl();
				SearchResult mySR = myInsertservice.insertPaper(theNewPaper);
				if (mySR.SUCCESS)
					result.append(XMLHelper.tagged("success", mySR.info));
				else {
					result.append(XMLHelper.tagged("failed", "insert"));
					result.append(XMLHelper.tagged("error", mySR.info));
				}
			} else {
				UpdateServiceImpl myUpdateservice = new UpdateServiceImpl();
				SearchResult mySR = myUpdateservice.updatePaper(theNewPaper);
				if (mySR.SUCCESS)
					result.append(XMLHelper.tagged("success", "Paper update succesfully"));
				else {
					result.append(XMLHelper.tagged("failed", "update"));
					result.append(XMLHelper.tagged("error", mySR.info));
				}

			}
			result.append(theNewPaper.toXML());
		} catch (Exception e) {
			result.append(XMLHelper.tagged("failed", ""));
			result.append(XMLHelper.tagged("error", e.toString()));
		}
		session.removeAttribute(SessionAttribs.PAPER);
		result.append(myNavCol.toString());
		result.append("</author>\n");
		response.setContentType("text/html; charset=ISO-8859-15");
		StreamSource xmlSource = new StreamSource(new StringReader(result
				.toString()));
		StreamSource xsltSource = new StreamSource(xslt);
		XMLHelper.process(xmlSource, xsltSource, out);
		out.flush();

	}

	public void doPost(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, java.io.IOException {
		doGet(request, response);
	}

}