package coma.servlet.servlets;

import java.io.*;
import java.util.*;


import javax.servlet.ServletConfig;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpSession;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.xml.transform.stream.StreamSource;

import coma.entities.*;
import coma.handler.db.ReadService;
import coma.servlet.util.*;

import coma.util.logging.ALogger;
import static coma.util.logging.Severity.*;
import static java.util.Arrays.asList;

/**
 * Flashy report concerning what's been rated how so far.
 *
 * @author ums, based on mal's work.
 */
public class ShowReports extends HttpServlet {

    private static final long serialVersionUID = 1L;

    private final ALogger LOG 
	= ALogger.create(this.getClass().getCanonicalName());

    StringBuffer result = new StringBuffer();
    XMLHelper helper = new XMLHelper();

    
    /**
       handle the request. 

       This servlet has one state, in which the reports currently
       visible to the user are shown. Coincidentally, they are sorted
       by paper.

       2005JAN11: we now check for the REPORTID session attribute. If
       that is present, we display a detailed view instead.
    */
    public void doGet(
		      HttpServletRequest request,
		      HttpServletResponse response) {
	try {

	    coma.entities.Person theUser = null;

	    helper.addXMLHead(result);
	    result.append("<result>\n");
	    result.append(new Navcolumn(request).toString());

	    HttpSession session = request.getSession(true);

	    try {
		theUser = (coma.entities.Person)session.getAttribute(SessionAttribs.PERSON);
	    } catch (IllegalStateException ise) {
		
		LOG.log(ERROR,"cannot look at session attributes", ise);
		helper.addError(UserMessage.ERRNOSESSION, result);

		// go on as usual, the rest must be able to handle
		// theUser==null.
	    }
	    

	    try {

		ReviewReport theReport 
		    = (ReviewReport)session.getAttribute(SessionAttribs.REPORT);

		
		if (theReport==null){

		    // if anything fails, catch is below.

		    // try 2: maybe the reportid is valid.
		    Integer theReportId = null; 
		    try {
			theReportId
			    = 0+(Integer)session.getAttribute(SessionAttribs.REPORTID);
		    } catch (Exception exce) {
			// try 3: maybe there's a form parameter
			theReportId
			    = Integer.parseInt(request.getParameter(FormParameters.REPORTID));
		    }

		    SearchCriteria theSC=new SearchCriteria();
		    theSC.setReviewReport(new ReviewReport(theReportId));
		    theReport = 
			((ReviewReport[])(new coma.handler.impl.db.ReadServiceImpl()
				    .getReviewReport(theSC).getResultObj()))[0];

		    if (theReport == null){
			
			// nope, let the user select one, then.
			throw new Exception("Goto! Goto! Goto!");
		    }
		}

		Paper thePaper = theReport.getPaper();
		
		if (!(getVisibleReviewReports(theUser, thePaper).contains(theReport))){
		    LOG.log(WARN, 
			    "Illegal access to report", theReport,
			    "on paper", thePaper,
			    "tried by user", theUser);
		    // GOTO! GOTO! GOTO!
		    throw new Exception("nope, can't see that one"); 
		}

		result.append(helper.tagged("pagetitle", "Detailed Review Report"));
		result.append(UserMessage.DETAILEDREPORT);
		result.append(theReport.toXML());
		

	    } catch (Exception tbl) { // on any error, display selection list instead.

		result.append(UserMessage.ALLREPORTSINTRO);
		result.append(helper.tagged("pagetitle","All reports")); //XXX
		result.append("<info>\n");

		try{

		    for (coma.entities.Paper thePaper: 
			     getVisiblePapers(theUser)){
	
			MultiMathReporter mr = new MultiMathReporter();

			result.append("<reportblock>");
			// This is highly redundant, but makes stuff easier.
			result.append(thePaper.toXML());
	    
			for (coma.entities.ReviewReport theReport: 
				 getVisibleReviewReports(theUser, thePaper)){
			
			    result.append(theReport.toXML());

			    mr.addReportRatings(theReport);
			}
			
			result.append(mr.toXML());
			result.append("</reportblock>");

		    }
		} catch (DatabaseDownException dbdown){
		    helper.addError(UserMessage.ERRDATABASEDOWN, result);
		} catch (UnauthorizedException unauth){
		    helper.addWarning(UserMessage.ERRUNAUTHORIZED, result);
		} finally {

		    // Hmmm... strange. Sometimes, this doesn't get executed.
		    result.append("</info>\n");
		}

	    } finally {

		result.append("</result>\n");
			
		response.setContentType("text/html; charset=ISO-8859-1");

		PrintWriter out = response.getWriter();

		StreamSource xmlSource =
		    new StreamSource(new StringReader(result.toString()));

		XMLHelper.process(xmlSource, 
				  coma.servlet.util.XSLT.file(this, "showreports"), 
				  out);
		out.flush();
	    }
	} catch (IOException e) {
	    //e.printStackTrace();
	    LOG.log(ERROR, e);
	} finally {
	    result = new StringBuffer();
	}
    }

    public void doPost(
		       HttpServletRequest request,
		       HttpServletResponse response) {
	doGet(request, response);
    }

    /**
       get all papers from the DB that the user is allowed to see.

       A user is allowed to see a paper iff
       the user is a chair,
       or the user is a reviewer of the paper and has already rated it.

       @param thePerson 
       the person that is asking for the information.
       May be null if person is not logged in.
       @return the set of papers the user is allowed to know about.
       The set may be empty, but it is never just null.
       @throws UnauthorizedException if the user is not logged in,
       i.e. thePerson is null
       @throws DatabaseDownException if an SQL Exception occurs.
    */
    Set<coma.entities.Paper> getVisiblePapers(coma.entities.Person thePerson)
	throws UnauthorizedException, DatabaseDownException {
	
	/* case 0: unauthorized user. In this case, we don't even need
	 * a DB connection or anything, we just don't tell anything.*/
	if (thePerson == null) { 
	    LOG.log(DEBUG,
		    "ShowReports", "unauthorized user requested reports");
	    throw new UnauthorizedException("<notAuthorized />");
	}

	/* after this, we potentially always need the DB. */

	Set<Paper> result= new HashSet<Paper>();

	/* XXX ziad should provide a factory for this */
	ReadService dbRead = new coma.handler.impl.db.ReadServiceImpl();
	SearchResult theSearchResult;

	/* case 1: a chair. In this case, we will show all papers
	   FIXME: do we need to exclude the case author==thePerson?
	   FIXME: should we exclude people who declined a paper for
	   pretty much the same reason, i.e. they're not impartial?
	   and all the info.	  
	*/
	if (thePerson.isChair()){

	    theSearchResult = dbRead.getPaper(new SearchCriteria());
	    postDBAccess(theSearchResult);

	    LOG.log(DEBUG, 
		    "should get info", "for chair");
		
	    result.addAll(asList((Paper[])theSearchResult.getResultObj()));
	    
	} else {
	    /* case 2: not a chair. In this case, we will show all
	       papers reviewed by thePerson that thePerson already has
	       rated.
	    */

	    SearchCriteria sc = new SearchCriteria();
	    sc.setPerson(thePerson);
	    theSearchResult = dbRead.getReviewReport(sc);
	    postDBAccess(theSearchResult);
	    Set<ReviewReport> allReports = 
		new HashSet<ReviewReport>(asList((ReviewReport[])theSearchResult.getResultObj()));

	    for (ReviewReport rr: allReports){

		if (rr.isEdited()){

		    result.add(rr.getPaper());
		}
	    }
	}

	return result;
    }

    /**
       For a given Person and a given Paper, get all visible
       ReviewReports from the DB. We define a RR to be visible iff the
       person is a chair or the RR is about a paper the person has
       rated (this implies the person must be a reviewer of this paper, too).

       @throws DatabaseDownException if a DB error occurs
       @throws UnauthorizedException if thePerson is null.
    */
    public Set<coma.entities.ReviewReport> 
	getVisibleReviewReports(coma.entities.Person thePerson, 
				coma.entities.Paper thePaper)
	throws DatabaseDownException, UnauthorizedException{

	/* case 0: unauthorized user. In this case, we don't even need
	 * a DB connection or anything, we just don't tell anything.*/
	if (thePerson == null) { 
	    LOG.log(DEBUG,
		    "ShowReports", "unauthorized user requested reports");
	    throw new UnauthorizedException("<notAuthorized />");
	}

	/* after this, we potentially always need the DB. */

	/* XXX ziad should provide a factory for this */
	ReadService dbRead = new coma.handler.impl.db.ReadServiceImpl();
	SearchResult theSearchResult;
	
	/* may only see other reports if already have rated themselves. */
	boolean hasRated = false;

	SearchCriteria sc = new SearchCriteria();
	sc.setPaper(thePaper);
	theSearchResult = dbRead.getReviewReport(sc);
	postDBAccess(theSearchResult);
	Set<ReviewReport> reportsOnThis = 
	    new HashSet<ReviewReport>(asList((ReviewReport[])theSearchResult.getResultObj()));
	
	for (ReviewReport rr: reportsOnThis){
	    
	    if (rr.getReviewer().equals(thePerson)
		&& rr.getRatings().size() > 0){
		
		hasRated = true;
		break;
	    }
	}

	return (hasRated)? reportsOnThis 
	    : new HashSet<ReviewReport>();
    }

    /**
       Convert database non-success into an Exception, the way G*d
       intended.

       @throws DatabaseDownException, if an DB access error occured
       while the passed SearchResult was generated.
    */
    private void postDBAccess(SearchResult theSearchResult) 
	throws DatabaseDownException {
	if (!theSearchResult.isSUCCESS()){
	    LOG.log(WARN,
		    "DB access failed:", theSearchResult.getInfo());
	    throw new DatabaseDownException(theSearchResult.getInfo());
	}
    }


}

// XXX move out into common structure, other people get Exceptions, too!
// XXX extend something more specific.
class UnauthorizedException extends Exception{
    private static final long serialVersionUID = 1L;
    public UnauthorizedException(String reason){super(reason);}
}

// XXX move out into common structure, other people get Exceptions, too!
// XXX extend something more specific.
class DatabaseDownException extends Exception{
    private static final long serialVersionUID = 1L;
    public DatabaseDownException(String reason){super(reason);}
}


