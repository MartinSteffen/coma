package coma.entities;

import static java.util.Arrays.asList;
import java.util.Set;

import coma.util.logging.ALogger;
import coma.util.logging.Severity;
import static coma.util.logging.Severity.*;

import coma.servlet.util.XMLHelper;
import coma.handler.db.ReadService;
import coma.servlet.util.XMLHelper;

/**
   Wrapper class for the DB Rating entries.

 */
public class Rating extends Entity {

    private Integer reviewReportId;
    private Integer criterionId;
    private Integer grade;
    private String comment;

    public Rating(){;}

    public int getReviewReportId(){return reviewReportId;}
    public int getCriterionId(){return criterionId;}
    
    public int getGrade(){return grade;}
    public String getComment(){return comment;}

    public void setGrade(int grade){this.grade=grade;}
    public void setComment(String c){this.comment=c;}

    public Criterion getCriterion(){

	ReadService rs = new coma.handler.impl.db.ReadServiceImpl();
	SearchCriteria sc = new SearchCriteria();
	sc.setCriterion(new Criterion(getCriterionId()));
	SearchResult sr = rs.getCriterion(sc);
	
	if (!sr.isSUCCESS()){
	    ALogger.log.log(WARN,
			    "Could not find Criterion ",
			    getCriterionId(), "in DB",
			    "for a Rating");
			    
	    return null;
	}

	Set<Criterion> cs 
	    = new java.util.HashSet<Criterion>(asList((Criterion[]) sr.getResultObj()));

	if (cs.size() != 1){
	    ALogger.log.log(WARN, 
			    "I found multiple criteria ",
			    cs,
			    "for a Rating");
	}
	for (Criterion c: cs){
	    return c;
	}
	ALogger.log.log(INFO, 
			"canthappen:",
			"suddenly a set of criteria is empty in", this);
	return null;
    }

    /**
       get the proper corresponding RR from the DB.
     */
    public ReviewReport getReviewReport(){

	ReadService rs = new coma.handler.impl.db.ReadServiceImpl();
	SearchCriteria sc = new SearchCriteria();
	sc.setReviewReport(new ReviewReport(getReviewReportId()));
	SearchResult sr = rs.getReviewReport(sc);
	
	if (!sr.isSUCCESS()){
	    ALogger.log.log(WARN,
			    "Could not find ReviewReport ",
			    getReviewReportId(), "in DB",
			    "for a Rating");
			    
	    return null;
	}

	Set<ReviewReport> cs 
	    = new java.util.HashSet<ReviewReport>(asList((ReviewReport[]) sr.getResultObj()));

	if (cs.size() != 1){
	    ALogger.log.log(WARN, 
			    "I found multiple criteria ",
			    cs,
			    "for a Rating");
	}
	for (ReviewReport r: cs){
	    return r;
	}
	ALogger.log.log(INFO, 
			"canthappen:",
			"suddenly a set of review reports is empty in", this);
	return null;
    }

    public StringBuilder toXML(XMLMODE mode){

	switch (mode) {
	    
	case DEEP:
	    return XMLHelper.tagged("rating",
				    getReviewReport().toXML(XMLMODE.SHALLOW),
				    getCriterion().toXML(XMLMODE.SHALLOW),
				    XMLHelper.tagged("grade", grade.toString()),
				    XMLHelper.tagged("comment", comment));

	case SHALLOW: 
	    return XMLHelper.tagged("rating",
				    XMLHelper.tagged("reviewReportId", ""+getReviewReportId()),
				    XMLHelper.tagged("criterionId", ""+getCriterionId()),
				    XMLHelper.tagged("grade", ""+getGrade()),
				    XMLHelper.tagged("comment", getComment())
				    );
	default:
	    coma.util.logging.ALogger.log.log(WARN, 
					      "unknown XMLMODE in",
					      this, ':', mode);
	    return null;
	}
    }
    
}
