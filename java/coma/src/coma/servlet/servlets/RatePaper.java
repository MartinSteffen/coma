package coma.servlet.servlets;

import  java.util.*;
import java.io.*;

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

	try {
	    StringBuffer result = new StringBuffer();
 
	    state = (Character)session.getAttribute(SessionAttribs.RATEPAPER_STATE);
	    if (state == null) {
		state='0'; // default state
	    }

	    switch (state) {
	    case '0':
		/*
		  "These are the papers you can rate."
		 */

		break;
	    case '1':
		/*
		  "You can now make changes to your RReport"
		 */

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
