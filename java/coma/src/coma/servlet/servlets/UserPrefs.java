package coma.servlet.servlets;

import java.io.PrintWriter;
import java.io.StringReader;

import javax.servlet.ServletConfig;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.xml.transform.stream.StreamSource;

import coma.entities.*;
import coma.handler.db.UpdateService;
import coma.servlet.util.*;
import coma.util.logging.ALogger;
import static coma.util.logging.Severity.*;
import static coma.entities.Entity.XMLMODE;

/**
   Servlet to let a user edit their preferences. Currently, only their
   preferred topics, and those are only important when the auto-assign
   alg. runs.

   @author ums, with large parts taken from the old Subscribe
*/
public class UserPrefs extends HttpServlet {

    private static final long serialVersionUID = 1L;

    final ALogger LOG = ALogger.create(this.getClass().getCanonicalName());
    
    /*
      N.b: we do not use an enumeration here because we cannot easily
      transfer an enum over the html contents. We can do that with
      chars.
    */
    public final class STATE {
	public static final char READ='r';
	public static final char WRITE='w';
    }
    
    /**
       handle the request.

       two states: READ: get data from db, answer with editable
       fields, go to state WRITE

       WRITE: read data from user forms, write that back to db.
    */
    public void doGet(
		      HttpServletRequest request,
		      HttpServletResponse response) throws java.io.IOException {

	HttpSession session;
	session = request.getSession(true);

	PageStateHelper pagestate = new PageStateHelper(request);

	StringBuffer result = new StringBuffer();
	XMLHelper x = new XMLHelper();

	Person thePerson = null;
	try {
	    thePerson = (Person)session.getAttribute(SessionAttribs.PERSON);	
	} catch (ClassCastException cce) {;}


	Conference theConference = null;
	try {
	    theConference = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
	} catch (ClassCastException cce) {;}

	x.addXMLHead(result);
	result.append("<content>");
	result.append(x.tagged("pagetitle","Edit User Information"));

	if ((thePerson == null) || (theConference==null)){

	    result.append(UserMessage.ERRUNAUTHORIZED);
	} else {

	    switch (pagestate.get()){

	    case PageStateHelper.NULLSTATE: //fall through
	    case STATE.READ:
		pagestate.set(STATE.WRITE);
		result.append(x.tagged("editable", thePerson.toXML()));
		result.append(x.tagged("topics",
				       Topic.manyToXML(Topic.allTopics(theConference), 
						       XMLMODE.DEEP)));
		break;
	    case STATE.WRITE:
		pagestate.set(STATE.READ);
		result.append(UserMessage.UPDATING);

		// change thePerson's attribs.
		// now, I'd like a functional map operator. And polymorphism.

		try{
		    if ((!thePerson.getPassword()
			.equals(request.getParameter(FormParameters.PASSWORD)))
			|| (!thePerson.getPassword()
			    .equals(request.getParameter("repassword")))){

			throw new UnauthorizedException("password mismatch");
		    }

		    int oldid = thePerson.getId();
		    thePerson 
			= new coma.handler.util.EntityCreater().getPerson(request);
		    thePerson.setId(oldid); // makes us safer against attacks
	    
		    try {
			Topic[] ts = thePerson.getPreferredTopics();
			if (ts != null){ // grrr...
			    for (int i=0; i<ts.length; ++i ){

				thePerson.deletePreferredTopic(ts[i]);
			    }
			}
			final String ptopics = request.getParameter(FormParameters.PREFERREDTOPICS);
			for (String s: ptopics.split("\\s*")){ //welcome to quoting hell!
			    try{
				
				thePerson.addPreferredTopic(new Topic(Integer.parseInt(s)));
				// (turns out Person just picks out the id anyway.)
							// Topic.byId(Integer.parseInt(s), 
							// theConference.getId()));
			    } catch (NumberFormatException nfe) {;}//just skip it, then.
			}
		    } catch (Exception exc) {
			; // Oh well, who cares about topics? FIXME
		    }
		
		    
		    SearchResult theSR;
		    UpdateService dbWrite 
			= new coma.handler.impl.db.UpdateServiceImpl();
		    theSR = dbWrite.updatePerson(thePerson);
		    if (!theSR.isSUCCESS()){
			throw new Exception(theSR.getInfo());
		    }
		    
		    session.setAttribute(SessionAttribs.PERSON, thePerson);
		} catch (UnauthorizedException uae){
		    
		    result.append(UserMessage.ERRUNAUTHORIZED);
		} catch (Exception exc){
		    // well, almost true.
		    result.append(UserMessage.ERRDATABASEDOWN);
		    result.append(x.tagged("error", exc.toString()));
		    
		}
		result.append(x.tagged("noneditable", thePerson.toXML()));
	    }
	}

	result.append(new Navcolumn(session));
	result.append(pagestate.toString());
	result.append("</content>");

	PrintWriter out = response.getWriter();
	response.setContentType("text/html; charset=ISO-8859-1");
	StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
	XMLHelper.process(xmlSource, XSLT.file(this, "userprefs"), out);
	out.flush();
	    
    }


    public void doPost(
		       HttpServletRequest request,
		       HttpServletResponse response) throws java.io.IOException {
	doGet(request, response);
    }


}
