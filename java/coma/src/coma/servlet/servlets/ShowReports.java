package coma.servlet.servlets;

import  java.util.*;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

import javax.naming.Context;
import javax.naming.InitialContext;
import javax.servlet.ServletConfig;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;
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
 * CURRENTLY NOT WORKING MUCH. Well, there's no db to check against anyway.
 *
 * @author ums, based on mal's work.
 */
public class ShowReports extends HttpServlet {

    private static final long serialVersionUID = 1L;

    private final ALogger LOG 
	= ALogger.create(this.getClass().getCanonicalName());

    StringBuffer info = new StringBuffer();
    StringBuffer result = new StringBuffer();
    XMLHelper helper = new XMLHelper();

    public void init(ServletConfig config) {
	LOG.log(DEBUG, "I'm alive!");
    }
    
    /**
       handle the request. 

       This servlet has one state, in which 
     */
    public void doGet(
		      HttpServletRequest request,
		      HttpServletResponse response) {
	try {

	    coma.entities.Person theUser = null;

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
			
	    String path = getServletContext().getRealPath("");
	    String xslt = path + "/" + "style/xsl/report.xsl";
	    PrintWriter out = response.getWriter();
			
	    result.append("<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>\n");
	    result.append("<result>\n");

	    result.append(UserMessage.ALLREPORTSINTRO);
	    result.append("<info>\n");

	    try{

		for (coma.entities.Paper thePaper: 
			 getVisiblePapers(theUser)){
		    
		    for (coma.entities.ReviewReport theReport: 
			     getVisibleReviewReports(theUser, thePaper)){
			
			result.append(theReport.toXML());
		    }
		}
	    } catch (DatabaseDownException dbdown){
		helper.addError(UserMessage.ERRDATABASEDOWN, result);
	    } catch (UnauthorizedException unauth){
		helper.addWarning(UserMessage.ERRUNAUTHORIZED, result);
	    }

	    result.append("</info>\n");
	    result.append("</result>\n");
			
	    response.setContentType("text/html; charset=ISO-8859-1");
	    StreamSource xmlSource =
		new StreamSource(new StringReader(result.toString()));
	    StreamSource xsltSource = new StreamSource(xslt);
	    XMLHelper.process(xmlSource, xsltSource, out);
	    out.flush();

	} catch (IOException e) {
	    e.printStackTrace();
	} finally {
	    info = new StringBuffer();
	    result = new StringBuffer();
	}
    }

    public void doPost(
		       HttpServletRequest request,
		       HttpServletResponse response) {
	doGet(request, response);
    }

    /**
       get all papers from the DB that the user is allowed to see

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
	if ((Math.random()>0.5)/*thePerson.isChair()*/){ //FIXME

	    theSearchResult = dbRead.getPaper(new SearchCriteria());
	    postAccess(theSearchResult);

	    LOG.log(DEBUG, 
		    "should get info", "for chair");
		
	    /* FIXME ziad must fix return type, maybe Set<> and not []? */
	    result.addAll(Arrays.asList((Paper[])theSearchResult.getResultObj()));
	    
	} else {
	    /* case 2: not a chair. In this case, we will show all
	       papers reviewed by thePerson that thePerson already has
	       rated.
	    */

	    /*FIXME: 
	      get all papers 
	      s.t. exists reviewreport R 
	      s.t. R->reviewerid == thePerson.id
	      AND exists rating S
	      s.t. S.review_id == R.id
	    */
	    
	    theSearchResult = dbRead.getReviewReport(new SearchCriteria());
	    postAccess(theSearchResult);
	    Set<ReviewReport> allReports = 
		new HashSet<ReviewReport>(Arrays.asList((ReviewReport[])theSearchResult.getResultObj()));

	    for (ReviewReport rr: allReports){

		if (rr.getReviewer().equals(thePerson)
		    && rr.getRatings().size() > 0){

		    result.add(rr.getPaper());
		}
	    }
	}

	return result;
    }

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
	/* FIXME only get reports on thePaper */
	theSearchResult = dbRead.getReviewReport(new SearchCriteria());
	postAccess(theSearchResult);
	Set<ReviewReport> reportsOnThis = 
	    new HashSet<ReviewReport>(Arrays.asList((ReviewReport[])theSearchResult.getResultObj()));
	
	for (ReviewReport rr: reportsOnThis){
	    
	    if (rr.getReviewer().equals(thePerson)
		&& rr.getRatings().size() > 0){
		
		hasRated = true;
	    }
	}

	return (hasRated)? reportsOnThis 
	                 : new HashSet<ReviewReport>();
    }

    private void postAccess(SearchResult theSearchResult) 
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
