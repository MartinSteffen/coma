package coma.entities;

import java.util.*;

import coma.util.logging.ALogger;
import coma.util.logging.Severity;
import static coma.util.logging.Severity.*;

import static java.util.Arrays.asList;
import java.util.Set;

import coma.servlet.util.XMLHelper;
import coma.handler.db.ReadService;
import coma.servlet.util.XMLHelper;

/**
 * @author mal, ums
 *
 */
public class ReviewReport extends Entity {

    private int id = -1;
    private int paperId = -1;
    private int reviewerId = -1;
    private String summary;
    private String remarks;
    private String confidental;

    public ReviewReport(int id){
	this.id=id;
    }

    public ReviewReport(){;}

    public int getId(){return id;}
    public int getPaperId(){return paperId;}
    public int getReviewerId(){return reviewerId;}
    public String getSummary(){return summary;}
    public String getRemarks(){return remarks;}
    public String getConfidental(){return confidental;}

    public Person getReviewer(){
	ReadService rs = new coma.handler.impl.db.ReadServiceImpl();
	SearchCriteria sc = new SearchCriteria();
	sc.setPerson(new Person(getReviewerId()));
	SearchResult sr = rs.getPerson(sc);
	
	if (!sr.isSUCCESS()){
	    ALogger.log.log(WARN,
			    "Could not find Reviewer ",
			    getReviewerId(), "in DB",
			    "for Report", this);
			    
	    return null;
	}

	Set<Person> cs 
	    = new java.util.HashSet<Person>(asList((Person[]) sr.getResultObj()));

	if (cs.size() != 1){
	    ALogger.log.log(WARN, 
			    "I found multiple reviewer ",
			    cs,
			    "for ReviewReport", this);
	}
	for (Person r: cs){
	    return r;
	}
	ALogger.log.log(INFO, 
			"canthappen:",
			"suddenly a set of Persons is empty in", this);
	return null;
    }

    public Paper getPaper(){
	ReadService rs = new coma.handler.impl.db.ReadServiceImpl();
	SearchCriteria sc = new SearchCriteria();
	sc.setPaper(new Paper(getPaperId()));
	SearchResult sr = rs.getPaper(sc);
	
	if (!sr.isSUCCESS()){
	    ALogger.log.log(WARN,
			    "Could not find Paper ",
			    getPaperId(), "in DB",
			    "for Report",this);
			    
	    return null;
	}

	Set<Paper> cs 
	    = new java.util.HashSet<Paper>(asList((Paper[]) sr.getResultObj()));

	if (cs.size() != 1){
	    ALogger.log.log(WARN, 
			    "I found multiple papers ",
			    cs,
			    "for a ReviewReport");
	}
	for (Paper r: cs){
	    return r;
	}
	ALogger.log.log(INFO, 
			"canthappen:",
			"suddenly a set of review reports is empty in", this);
	return null;
    }

    /**
       Return a set of all ratings that are part of this review report.

       The set returned may be empty, but it is never null.
     */
    public Set<Rating> getRatings(){
	Set<Rating> result = new HashSet<Rating>();

	ReadService rs = new coma.handler.impl.db.ReadServiceImpl();
	SearchCriteria sc = new SearchCriteria();
	sc.setReviewReport(this);
	SearchResult sr = rs.getRating(sc);
	
	if (!sr.isSUCCESS()){
	    ALogger.log.log(WARN,
			    "Could not find Ratings",
			    "in DB", "for RReport", this);
			    
	    return result;
	}

	result.addAll(asList((Rating[]) sr.getResultObj()));

	assert (result != null) 
	    : "violates spec: result null";
	return result;
    }

    public StringBuilder toXML(XMLMODE mode){

	switch (mode){
	case SHALLOW:
	    return XMLHelper.tagged("ReviewReport",
				    XMLHelper.tagged("id", ""+getId()),
				    XMLHelper.tagged("paperId", ""+getPaperId()),
				    XMLHelper.tagged("reviewerId", ""+getReviewerId()),
				    XMLHelper.tagged("summary", getSummary()),
				    XMLHelper.tagged("remarks", getRemarks()),
				    XMLHelper.tagged("confidental", getConfidental())
				    );
	case DEEP:
	    return XMLHelper.tagged("ReviewReport",
				    XMLHelper.tagged("id", ""+getId()),
				    //FIXME not Entity yet getPaper().toXML(XMLMODE.SHALLOW),
				    //FIXME not Entity yet getReviewer().toXML(XMLMODE.SHALLOW),
				    XMLHelper.tagged("summary", getSummary()),
				    XMLHelper.tagged("remarks", getRemarks()),
				    XMLHelper.tagged("confidental", getConfidental())
				    );
	  
	default:
	    coma.util.logging.ALogger.log.log(WARN, 
					      "unknown XMLMODE in",
					      this, ':', mode);
	    return null;
	}
    }
}
