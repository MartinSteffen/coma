package coma.servlet.servlets;

import  java.util.*;
import static java.util.Arrays.asList;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;

import javax.naming.Context;
import javax.naming.InitialContext;
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

    public void init(ServletConfig config) {
	LOG.log(DEBUG, "I'm alive!");
    }
    
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

	    try {
		theUser = 
		    (coma.entities.Person)request.getSession(true)
		    .getAttribute(SessionAttribs.PERSON);
	    } catch (IllegalStateException ise) {
		
		LOG.log(ERROR,"cannot look at session attributes", ise);
		helper.addError(UserMessage.ERRNOSESSION, result);

		// go on as usual, the rest must be able to handle
		// theUser==null.
	    }
	    

	    try {

		ReviewReport theReport 
		    = (ReviewReport)request.getSession(false).getAttribute(SessionAttribs.REPORT);

		
		if (theReport==null){
		    // nope, let the user select one, then.
		    throw new Exception("Goto! Goto! Goto!");
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

		result.append(theReport.toXML());
		

	    } catch (Throwable tbl) { // on any error, display selection list instead.

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
		}

		result.append("</info>\n");

	    } finally {

		result.append("</result>\n");
			
		response.setContentType("text/html; charset=ISO-8859-1");

		String path = getServletContext().getRealPath("");
		String xslt = path + "/style/xsl/showreports.xsl";
		PrintWriter out = response.getWriter();

		StreamSource xmlSource =
		    new StreamSource(new StringReader(result.toString()));
		StreamSource xsltSource = new StreamSource(xslt);
		XMLHelper.process(xmlSource, xsltSource, out);
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

		if (rr.getRatings().size() > 0){

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

    // Just test routines.
    public static void main(String[] args){
	
	MultiMathReporter mmr = new MultiMathReporter();
	System.err.println(mmr.mean(3,4,5,6));
	System.err.println(mmr.rms(3,4,5,6));
	/* ==> 4.5, 1.1180...  */

	System.err.println(mmr.rms(6,6,6,7));
	System.err.println(mmr.rms(3,3,3,4));

	System.err.println(mmr.mean(1,4,5,8));
	System.err.println(mmr.rms(1,4,5,8));
	/* ==> 4.5, 2.5 */

	System.err.println(mmr.mean(1,0,4,5,8,0,0,0));
	System.err.println(mmr.rms(1,0,4,5,0,8));
	/* ==> 4.5, 2.5 */

	System.err.println(mmr.mean(1,0,4,5,8,0,-3,-666));
	System.err.println(mmr.rms(1,0,4,5,-666,8));
	/* ==> 4.5, 2.5 */

	System.err.println(mmr.toXML());
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

/**
   A helper class for statistics.

   Throw in a lot of ReviewReports, get out average and rms-estimate
   for each Criterion involved.

   we use the following formula for rms, when we have n reports, and i
   always runs over n:

   mean = 1/n \sum_i x_i

   rms  = sqrt((N\sum_i x_i^2 -(\sum_i x_i)^2 )/n) 

   TODO: Currently, we do not care about those priority factors.
*/
class MultiMathReporter {

    /** A dummy var that indicates Array of Integer to Collection.toArray*/
    private static final Integer[] INTARRAY_MOLD=null;

    java.util.Map<String, Collection<Integer>> ratings
	= new TreeMap<String, Collection<Integer>>();

    java.util.Map<String, Integer> maxvals
	= new TreeMap<String, Integer>();

    java.util.Map<String, Integer> prios
	= new TreeMap<String, Integer>();

    
    public MultiMathReporter(){;}

    /**
       put in another ReviewReport that should go into the maths.
    */
    public void addReportRatings(ReviewReport rr){
	for (Rating rat: rr.getRatings()){

	    Criterion crit = rat.getCriterion();
	    
	    Collection<Integer> grades = ratings.get(crit.getName());
	    if (grades==null){
		grades = new java.util.ArrayList<Integer>();
	    }
	    grades.add(rat.getGrade());
	    ratings.put(crit.getName(), grades);
	    maxvals.put(crit.getName(), crit.getMaxValue());
	    prios.put(crit.getName(), crit.getQualityRating());
	}
    }

    /**
       calculate and return all that we know.
    */
    public CharSequence toXML(){
	StringBuilder result = new StringBuilder();
	result.append("<statistics>");
	for (String critname: ratings.keySet()){
	    result.append("<criterion name=\""+critname+"\">");
	    result.append(XMLHelper.tagged("mean", mean(ratings.get(critname).toArray(INTARRAY_MOLD))));
	    result.append(XMLHelper.tagged("rms", rms(ratings.get(critname).toArray(INTARRAY_MOLD))));
	    result.append("</criterion>");
	}
	result.append("</statistics>");
	return result;
    }

    /**
       Return the mean of all passed integers that are at least 1.
       
       We skip all other integers. This means that this is safe for
       almost any kind of initialisation the Chair people make up for
       Reports.
    */
    Double mean(Integer... xs){
	Double result = 0.0;
	int n = 0;
	for (Integer x: xs){
	    if (x==null) continue;
	    if (x >= 1){
		result += x;
		n++;
	    }
	}
	return result/(1.0*n);
    }

    /**
       Return the rms of all passed integers that are at least 1.
       
       We skip all other integers. This means that this is safe for
       almost any kind of initialisation the Chair people make up for
       Reports.

       rms is a measure of the deviation of the points, i.e. high
       values are a sign of equivocality.
    */
    Double rms(Integer... xs){
	Double sqsum = 0.0;
	Double sum = 0.0;
	double n = 0;
	for (Integer x:xs){
	    if (x==null) continue;
	    if (x >= 1){
		sum += x;
		sqsum += (x*x);
		n++;
	    }
	}
	return Math.sqrt((n*sqsum - sum*sum)/(n*n));
	
    }

}
