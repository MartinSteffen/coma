package coma.entities;

import java.util.*;

import coma.util.logging.ALogger;
import coma.util.logging.Severity;
import static coma.util.logging.Severity.*;

import coma.servlet.util.XMLHelper;

/**
 * @author mal, ums
 *
 */
public class ReviewReport extends Entity {

    private int id;
    private int paperId;
    private int reviewerId;
    private String summary;
    private String remarks;
    private String confidental;

    public ReviewReport(int id){
	this.id=id;
    }

    public ReviewReport(){
    }

    public int getId(){return id;}
    public int getPaperId(){return paperId;}
    public int getReviewerId(){return reviewerId;}
    public String getSummary(){return summary;}
    public String getRemarks(){return remarks;}
    public String getConfidental(){return confidental;}

    public Person getReviewer(){
	/* FIXME */
	return null;
    }

    public Paper getPaper(){
	/* FIXME */
	return null;
    }

    /**
       Return a set of all ratings that are part of this review report.

       The set returned may be empty, but it is never null.
     */
    public Set<Rating> getRatings(){
	Set<Rating> result = new HashSet<Rating>();
	/* FIXME */
	assert (result != null) 
	    : "violates spec: result null";
	return result;
    }

    public StringBuilder toXML(XMLMODE mode){

	StringBuilder result = new StringBuilder();

	switch (mode){ /* FIXME */
	case SHALLOW:
	    return XMLHelper.tagged("ReviewReport"
				    );
	case DEEP:
	    break;
	default:
	    coma.util.logging.ALogger.log.log(WARN, 
					      "unknown XMLMODE in",
					      this, ':', mode);
	}
	return result;
    }
}
