package coma.servlet.servlets;

import java.io.PrintWriter;
import java.io.StringReader;
import java.util.HashSet;
import java.util.Set;

import javax.servlet.ServletConfig;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.xml.transform.stream.StreamSource;

import coma.entities.*;
import coma.handler.db.*;
import coma.servlet.util.*;
import coma.util.logging.ALogger;
import static coma.util.logging.Severity.*;
import static java.util.Arrays.asList;

/** 
    Rating a paper

    @author ums
*/
public class RatePaper extends HttpServlet{

    private static final long serialVersionUID = 1L;

    final ALogger LOG = ALogger.create(this.getClass().getCanonicalName());


    /*
      N.b: we do not use an enumeration here because we cannot easily
      transfer an enum over the html contents. We can do that with
      chars.
    */
    public final class STATE {
	public static final char SELECTPAPER='0';
	public static final char EDITREPORT ='1';
	public static final char UPDATE_DB ='2';
	public static final char ERROR      ='e';
    }
    
    /**
       handle the request. <p />

       This servlet has 4 states: <p />

       SELECTPAPER: selecting a paper that the user is allowed to
       rate, i.e. we show all those papers along with the user's RR on
       them, where there already is one. -&gt;EDITREPORT <p />

       EDITREPORT: having selected a paper, displaying the report that
       was already there in a way that can be edited. -&gt;UPDATE_DB <p />

       UPDATE_DB: with changes made by the user, update the entry in
       the DB. Give the user a "thank you bla bla" message, -&gt;
       SELECTPAPER, possiby with auto-redirect after 5 seconds or so?
       <p />
       
       ERROR: the user is not logged in. Say "bzzzzzt! Go away,
       fool!".<p />

       Note: the update is <b>not</b> atomic. Especially, the ratings
       are updated seperately from all textual remarks.
       
    */
    public void doGet(
		      HttpServletRequest request,
		      HttpServletResponse response) 
    throws java.io.IOException {

	HttpSession session;
	session = request.getSession(true);

	PageStateHelper pagestate = new PageStateHelper(request);

	StringBuffer result = new StringBuffer();
	XMLHelper x = new XMLHelper();

	ReadService dbRead = new coma.handler.impl.db.ReadServiceImpl();
	SearchResult theSR;
	SearchCriteria theSC = new SearchCriteria();
	Person thePerson = (Person)session.getAttribute(SessionAttribs.PERSON);	
	ReviewReport theReport;

	Conference theConference 
	    = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
 
	x.addXMLHead(result);
	result.append("<content>");
	result.append(new Navcolumn(request).toString());

	try {

	    switch (pagestate.get()) {

	    case PageStateHelper.NULLSTATE: //fall through
	    case STATE.SELECTPAPER: {
		/*
		  "These are the papers you can rate."
		*/

		// Too late to rate?
		if (new java.util.Date()
		    .after(((Conference)session
			    .getAttribute(SessionAttribs.CONFERENCE))
			   .getReview_deadline())){

		    result.append(UserMessage.ERRTOOLATE);
		    pagestate.set(STATE.ERROR);
		    
		} else {
		
		    result.append(UserMessage.PAPERS_TO_RATE);

		    theReport = new ReviewReport(-1);
		    theReport.setReviewerId(thePerson.getId());
		    theSC.setReviewReport(theReport);
		    theSR = dbRead.getReviewReport(theSC);
		    Set<ReviewReport> reports 
			= new HashSet<ReviewReport>(asList((ReviewReport[])theSR.getResultObj()));

		    LOG.log(DEBUG, "report object:", theSR.getResultObj());
		    LOG.log(DEBUG, "found reports:", reports.size());

		    for (ReviewReport report: reports){

			result.append(x.tagged("selectpaper", report.getPaper().toXML()));
		    }
		    result.append(UserMessage.SUBMITBUTTON);

		    if (thePerson != null){
			pagestate.set(STATE.EDITREPORT);
		    } else {
			pagestate.set(STATE.ERROR);
		    }
		}
		break;
	    }

	    case STATE.EDITREPORT: {
		/*
		  "You can now make changes to your RReport"
		*/

		LOG.log(DEBUG, "paperid: ",request.getParameter(SessionAttribs.PAPERID));

		int thePaperID 
		    = Integer.parseInt(request.getParameter(SessionAttribs.PAPERID));
		session.setAttribute(SessionAttribs.PAPERID, new Integer(thePaperID));

		theSC=new SearchCriteria();
		theSC.setPaper(new Paper(thePaperID));
		theSR = dbRead.getPaper(theSC);
		LOG.log(DEBUG, "proper papers:", theSR.getResultObj());
		session.setAttribute(SessionAttribs.PAPER,
				     ((Paper[])theSR.getResultObj())[0]);

		result.append(UserMessage.EDITREPORT);


		theSC=new SearchCriteria();
		theReport = new ReviewReport(-1);
		LOG.log(DEBUG, "thePerson is:", thePerson);
		theReport.setReviewerId(thePerson.getId());
		theReport.setPaperId(thePaperID);
		theSC.setReviewReport(theReport);


		theSR = dbRead.getReviewReport(theSC);
		LOG.log(DEBUG, "Reports:", theSR.getResultObj());

		if (((ReviewReport[])theSR.getResultObj()).length != 1){

		    LOG.log(ERROR, 
			    "DB inconsistency:",
			    ((ReviewReport[])theSR.getResultObj()).length,
			    "!=1 RReports", theSR);
		    pagestate.set(STATE.ERROR);

		} //else { // all is well in the land of Denmark.

		    theReport = ((ReviewReport[])theSR.getResultObj())[0];

		    LOG.log(DEBUG, "The proper report:", theReport);

		    session.setAttribute(SessionAttribs.REPORTID, 
					 new Integer(theReport.getId()));
		    session.setAttribute(SessionAttribs.REPORT, 
					 theReport);

		    result.append(x.tagged("editReport", theReport.toXML()));
		    
		    result.append(UserMessage.SUBMITBUTTON);
		    pagestate.set(STATE.UPDATE_DB);
		
		    //}
		break;
	    }

	    case STATE.UPDATE_DB: {
		LOG.log(DEBUG, "saving to DB");
		/*
		  "Thank you for your cooperation."
		*/

		/*
		  Whoopsie! Even reviewers can be late! We fail
		  here as well for safety reasons: maybe the
		  deadline passed while the reviewer was typing.
		  Well, that's bad luck, then.
		*/
		if (new java.util.Date()
		    .after(((Conference)session
			    .getAttribute(SessionAttribs.CONFERENCE))
			   .getReview_deadline())){
		    result.append(UserMessage.ERRTOOLATE);
		    pagestate.set(STATE.ERROR);
		 
		} else {

		
		    try { // XXX any error in this will yield a db error.


			theReport 
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
			// XXX ugly
			result.append(x.tagged("meta", 
					       "<meta http-equiv=\"refresh\"", 
					       "content=\"5 url=RatePaper?servletState=_\" />"));
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
			pagestate.set(STATE.SELECTPAPER);
			// forget about the report, else we'll have problems
			// ever rating anything else.
			session.setAttribute(SessionAttribs.REPORTID, null);
			session.setAttribute(SessionAttribs.REPORT, null);
		    }
		}
	    }
		break;
	    case STATE.ERROR: //fall through
	    default: {
		/*
		  Pretty generic error state: user not authorized
		*/
		result.append(UserMessage.ERRUNAUTHORIZED);
	    }
		break;
	    }


	} catch (Exception exc) { // safety net

	    LOG.log(ERROR, exc);
	} finally {
	    result.append(pagestate.toString());
	    result.append("</content>");

	    PrintWriter out = response.getWriter();
	    response.setContentType("text/html; charset=ISO-8859-1");
	    StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
	    XMLHelper.process(xmlSource, coma.servlet.util.XSLT.file(this, "ratepaper"), out);
	    out.flush();
	}
    }

    public void doPost(
		       HttpServletRequest request,
		       HttpServletResponse response) 
	throws java.io.IOException {
	doGet(request, response);
    }


}
