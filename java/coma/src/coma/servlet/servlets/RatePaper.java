package coma.servlet.servlets;

import  java.util.*;
import java.io.*;
import static java.util.Arrays.asList;

import javax.servlet.ServletConfig;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.xml.transform.Source;
import javax.xml.transform.stream.StreamSource;

import coma.servlet.util.XMLHelper;

import coma.util.logging.ALogger;
import coma.util.logging.Severity;
import static coma.util.logging.Severity.*;

import coma.servlet.util.SessionAttribs;
import coma.servlet.util.UserMessage;

import coma.handler.db.*;
import coma.entities.*;


/** 
    Rating a paper

    @author ums
*/
public class RatePaper extends HttpServlet{

    private static final long serialVersionUID = 1L;

    final ALogger LOG = ALogger.create(this.getClass().getCanonicalName());

    public void init(ServletConfig config) {
	LOG.log(DEBUG, "I'm alive!");
    }
    
    /**
       handle the request. 

       This servlet has 4 states:

       S0: selecting a paper that the user is allowed to rate, i.e. we
           show all those papers along with the user's RR on them, where
	   there already is one. ->S1

       S1: having selected a paper, displaying the report that was
           already there in a way that can be edited. -> S2

       S2: with changes made by the user, update the entry in the DB.
           Give the user a "thank you bla bla" message, -> S0, possiby
           with auto-redirect after 5 seconds or so?

       Se: the user is not logged in. Say "bzzzzzt! Go away, fool!".
     */
    public void doGet(
		      HttpServletRequest request,
		      HttpServletResponse response) {

	HttpSession session;
	session = request.getSession(true);
	Character state = null;
	StringBuffer result = new StringBuffer();
	XMLHelper x = new XMLHelper();

	ReadService dbRead = new coma.handler.impl.db.ReadServiceImpl();
	SearchResult theSR;
	SearchCriteria theSC = new SearchCriteria();
	Person thePerson = (Person)session.getAttribute(SessionAttribs.PERSON);	
 
	try {

	    state = (Character)session.getAttribute(SessionAttribs.RATEPAPER_STATE);
	    if (state == null) {
		state='0'; // default state
	    }

	    switch (state) {
	    case '0':
		/*
		  "These are the papers you can rate."
		 */
		x.addContent(UserMessage.PAPERS_TO_RATE, result);


		theSC.setPerson(thePerson);
		theSR = dbRead.getReviewReport(theSC);
		if (!theSR.isSUCCESS())
		    throw new Exception("Can't happen"); // XXX, but ziad asserts this.
		Set<ReviewReport> reports 
		    = new HashSet<ReviewReport>(asList((ReviewReport[])theSR.getResultObj()));

		/*
		  TODO: the xslt should transform <selectpaper>(paper)</selectpaper> to a radio button
		        that will leave its ID in SessionAttribs.PAPERID.
			FIXME It actually cannot put it into a S.A. directly.
		 */
		for (ReviewReport report: reports){
		    // FIXME Paper isn't Entity yet.
		    result.append(x.tagged("selectpaper", report.getPaper().toString()/*XXX XML()*/));
		}
		result.append(UserMessage.SUBMITBUTTON);

		if (thePerson != null){
		    state = '1';
		} else {
		    state='e';
		}

		break;
	    case '1':
		/*
		  "You can now make changes to your RReport"
		 */


		int thePaperID = ((Integer)session.getAttribute(SessionAttribs.PAPERID)).intValue();

		result.append(UserMessage.EDITREPORT);
		

		theSC.setPerson(thePerson);
		theSC.setPaper(new Paper(thePaperID));

		theSR = dbRead.getReviewReport(theSC);

		if ((!theSR.isSUCCESS())
		    || (((ReviewReport[])theSR.getResultObj()).length != 1)){

		    LOG.log(ERROR, "DB inconsistency: !=1 RReports", theSR);
		    state = '0';

		} else { // all is well in the land of Denmark.

		    ReviewReport theReport = ((ReviewReport[])theSR.getResultObj())[0];

		    session.setAttribute(SessionAttribs.REPORTID, new Integer(theReport.getId()));
		    session.setAttribute(SessionAttribs.REPORT, theReport);

		    /*
		      TODO: the XSLT should generate editable fields
		      for the Report, with uneditable fields for
		      things like paper name etc.
		     */
		    result.append(x.tagged("editReport", theReport.toXML()));
		    
		    result.append(UserMessage.SUBMITBUTTON);
		    state = '2';

		}
		break;
	    case '2':
		/*
		  "Thank you for your cooperation."
		 */
		

		break;
	    case 'e':
		/*
		  Pretty generic error state: user not authorized
		 */

		break;
	    case '?': //fall through
	    default:
	    }

	    /*FIXME FIXME FIXME*/
	    String xslt = "jakarta-tomcat-5.0.28/webapps/coma/style/xsl/login.xsl";
	    PrintWriter out = response.getWriter();
	    response.setContentType("text/html; charset=ISO-8859-1");
	    StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
	    StreamSource xsltSource = new StreamSource(xslt);
	    XMLHelper.process(xmlSource, xsltSource, out);
	    out.flush();

	} catch (Exception exc) { // safety net

	    LOG.log(ERROR, exc);
	} finally {


	    if (session != null){
		session.setAttribute(SessionAttribs.RATEPAPER_STATE, state);
	    }
	}
    }

    public void doPost(
		       HttpServletRequest request,
		       HttpServletResponse response) {
	doGet(request, response);
    }


}
