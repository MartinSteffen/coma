package coma.servlet.util;

import java.util.Date;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import coma.entities.Person;
import coma.util.logging.ALogger;
import static coma.util.logging.Severity.ERROR;

/**
   Java helper class to create the Navigation columns in the output.

   Just get an instance of this class and toString() it into the
   output. It will ask the session for information about the current
   user and do the right thing.

   In your xsl, add the following:
   
   Outside of any template, but within the stylesheet:
   <code>&lt;xsl:include href="navcolumn.xsl" /&gt;</code>
   to include the navcolumn's formatting,

   Somewhere within your template: <code>&lt;xsl:call-template
   name="navcolumn" /&gt;</code> Make sure it is in a place where it
   will go to the body region of the HTML.

 */
public class Navcolumn {

    HttpSession hsession;

    public Navcolumn(HttpSession hs){
	hsession=hs;
    }

    public Navcolumn(HttpServletRequest hsr){
	this(hsr.getSession(true));
    }

    public String toString(){

	Person p=null;
	try{
	    p = (Person)hsession.getAttribute(SessionAttribs.PERSON);
	} catch (ClassCastException cce){

	    ALogger.log.log(ERROR, "Session Attrib PERSON was not a Person",
			    cce);

	}

	return tagged("navcolumn",
		      tagged("theTime", new Date()),
		      (p==null)? tagged("noUser") : "",
		      ((p != null) 
		       && (p.isChair()))? tagged("isChair") : "",
		      ((p != null) 
		       && (p.isAuthor()))? tagged("isAuthor") : "",
		      ((p != null) 
		       && (p.isReviewer()))? tagged("isReviewer") : "")
	    .toString();
    }

}
