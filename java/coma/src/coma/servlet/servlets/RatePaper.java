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

import coma.util.logging.ALogger;
import coma.util.logging.Severity;
import static coma.util.logging.Severity.*;

import coma.servlet.util.*;

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
       handle the request. <p />

       This servlet has 4 states: <p />

       S0: selecting a paper that the user is allowed to rate, i.e. we
       show all those papers along with the user's RR on them, where
       there already is one. ->S1 <p />

       S1: having selected a paper, displaying the report that was
       already there in a way that can be edited. -> S2 <p />

       S2: with changes made by the user, update the entry in the DB.
       Give the user a "thank you bla bla" message, -> S0, possiby
       with auto-redirect after 5 seconds or so? <p />
       
       Se: the user is not logged in. Say "bzzzzzt! Go away, fool!".<p />

       Note: the update is <b>not</b> atomic. Especially, the ratings
       are updated seperately from all textual remarks.
       
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
 
	x.addXMLHead(result);
	result.append("<content>");

	try {

	    

	    state = (Character)session.getAttribute(SessionAttribs.RATEPAPER_STATE);
	    if (state == null) {
		state='0'; // default state
	    }

	    switch (state) {
	    case '0': {
		/*
		  "These are the papers you can rate."
		*/
		result.append(UserMessage.PAPERS_TO_RATE);


		theSC.setPerson(thePerson);
		theSR = dbRead.getReviewReport(theSC);
		if (!theSR.isSUCCESS())
		    throw new Exception("Can't happen"); // XXX, but ziad asserts this.
		Set<ReviewReport> reports 
		    = new HashSet<ReviewReport>(asList((ReviewReport[])theSR.getResultObj()));

		/*
		  TODO: the xslt should transform <selectpaper>(paper)</selectpaper> to a radio button
		  that will leave its ID in request.getParameter(SessionAttribs.PAPERID).

		*/
		for (ReviewReport report: reports){

		    result.append(x.tagged("selectpaper", report.getPaper().toXML()));
		}
		result.append(UserMessage.SUBMITBUTTON);

		if (thePerson != null){
		    state = '1';
		} else {
		    state='e';
		}
	    }
		break;
	    case '1': {
		/*
		  "You can now make changes to your RReport"
		*/

		int thePaperID = Integer.parseInt(request.getParameter(SessionAttribs.PAPERID));
		session.setAttribute(SessionAttribs.PAPERID, new Integer(thePaperID));

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

		      the field names must match the FormParamater declarations.
		      the ratings attribs must be reachable by 
		      FormParameter.RATING_PREFIX+rating.getCriterionID()
		      + FormParamter.RATING_POSTFIX_FOO
		    */
		    result.append(x.tagged("editReport", theReport.toXML()));
		    
		    result.append(UserMessage.SUBMITBUTTON);
		    state = '2';

		}}
		break;
	    case '2': {
		/*
		  "Thank you for your cooperation."
		*/
		
		try { // XXX any error in this will yield a db error.

		    final ReviewReport theReport 
			= (ReviewReport)session.getAttribute(SessionAttribs.REPORT);
		    if (theReport == null){ 
			LOG.log(ERROR, 
				"I found no ReviewReport in Session",
				session,
				"in state s2 where there must be one."
				);
			throw new NullPointerException("no report present.");
		    }

		    final int thePaperID
			= ((Integer)session.getAttribute(SessionAttribs.PAPERID)).intValue();

		    // get Summary
		    final String theSummary 
			= request.getParameter(FormParameters.SUMMARY);
		    if (theSummary != null){
			theReport.setSummary(theSummary);
		    }

		    final String theRemarks
			= request.getParameter(FormParameters.REMARKS);
		    if (theRemarks != null){
			theReport.setRemarks(theRemarks);
		    }

		    final String theConfidental
			= request.getParameter(FormParameters.CONFIDENTAL);
		    if (theConfidental != null){
			theReport.setConfidental(theConfidental);
		    }

		    result.append(UserMessage.UPDATING);
		    UpdateService dbUpd = new coma.handler.impl.db.UpdateServiceImpl();
		    SearchResult sr;

		    for (Rating aRating: theReport.getRatings()){

			int grade = Integer.parseInt
			    (request.getParameter(FormParameters.RATING_PREFIX
						  + aRating.getCriterionId()
						  +FormParameters.RATING_POSTFIX_GRADE));
			// ^^^ XXX that is ugly, but Ratings don't have IDs.

			String cmt
			    = request.getParameter(FormParameters.RATING_PREFIX
						   + aRating.getCriterionId()
						   +FormParameters.RATING_POSTFIX_COMMENT);

			if (cmt != null){
			    aRating.setComment(cmt);
			}
			aRating.setGrade(grade);

			sr = dbUpd.updateRating(aRating);
			if (!sr.isSUCCESS()){
			    x.addError(sr.getInfo(), result);
			    LOG.log(WARN, 
				    "DB update of rating", aRating, 
				    "for report", theReport,
				    "failed:",
				    sr.getInfo());
			    throw new DatabaseDownException(sr.getInfo());
			}

			
		    }

		    sr = dbUpd.updateReviewReport(theReport);
		    if (!sr.isSUCCESS()){
			x.addError(sr.getInfo(), result);
			LOG.log(WARN, 
				"DB update of report", theReport, "failed:",
				sr.getInfo());
			throw new DatabaseDownException(sr.getInfo());
		    }
			

		} catch (Exception exc){

		    // well, this is close enough to the truth for the moment.
		    result.append(UserMessage.ERRDATABASEDOWN);
		} finally {
		    state = '0';
		}
	    }
		break;
	    case '?': //fall through
	    case 'e': //fall through 
	    default: {
		/*
		  Pretty generic error state: user not authorized
		*/
		result.append(UserMessage.ERRUNAUTHORIZED);
	    }
		break;
	    }

	    result.append("</content>");

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
