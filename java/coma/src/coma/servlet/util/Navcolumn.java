package coma.servlet.util;

import java.util.Date;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import coma.entities.Conference;
import coma.entities.Person;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.entities.Entity.XMLMODE;
import coma.handler.impl.db.ReadServiceImpl;
import coma.util.logging.ALogger;
import static coma.util.logging.Severity.*;



import static coma.servlet.util.XMLHelper.tagged;


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

    java.util.Set<String> extradata = new java.util.HashSet<String>();

    public Navcolumn(HttpSession hs){
	hsession=hs;
    }

    public Navcolumn(HttpServletRequest hsr){
	this(hsr.getSession(true));
    }

    public void addExtraData(String s){
	extradata.add(s);
    }

    public String toString(){

	Person p=null;
	StringBuilder result= new StringBuilder();
	try{
	    p = (Person)hsession.getAttribute(SessionAttribs.PERSON);
	} catch (ClassCastException cce){

	    ALogger.log.log(ERROR, "Session Attrib PERSON was not a Person",
			    cce);

	}

// 	return tagged("navcolumn",
// 		      tagged("theTime", new Date()),
// 		      (p==null)? tagged("noUser") : "",
// 		      ((p != null) 
// 		       && (p.isChair()))? tagged("isChair") : "",
// 		      ((p != null) 
// 		       && (p.isAuthor()))? tagged("isAuthor") : "",
// 		      ((p != null) 
// 		       && (p.isReviewer()))? tagged("isReviewer") : "")
// 	    .toString();
	result.append(tagged("theTime", new Date()));
	if ( p == null ){
		ReadServiceImpl myReadService = new ReadServiceImpl();
		Conference mySearchconference = new Conference(-1);
		SearchCriteria mysc = new SearchCriteria();
		mysc.setConference(mySearchconference);
		SearchResult mySR = myReadService.getConference(mysc);
		String extraData = "";
		if (mySR != null){
			Conference[] confernceArray = (Conference[]) mySR.getResultObj();
 			extraData="<conference_list>\n";
 			for (int i = 0; i < confernceArray.length; i++) {
 				extraData+=(((Conference)confernceArray[i]).toXML(XMLMODE.SHALLOW)).toString();
 			}
 			extraData+="</conference_list>\n";
 			result.append(extraData);
 			
			String info = mySR.getInfo();
			System.out.println(info);
		}
			
			result.append(extraData);
	    result.append(tagged("noUser"));
	} else {
	    result.append(p.toXML(XMLMODE.SHALLOW));
	    if (p.isChair())
		result.append(tagged("isChair"));
	    if (p.isAuthor())
		result.append(tagged("isAuthor"));
	    if (p.isReviewer())
		result.append(tagged("isReviewer"));
	}
	for (String s: extradata){
	    result.append(s);
	}
	return tagged("navcolumn", result).toString();
    }

}
