package coma.entities;

import coma.util.logging.ALogger;
import coma.util.logging.Severity;
import static coma.util.logging.Severity.*;

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
	/* FIXME */
	return null;
    }

    public ReviewReport getReviewReport(){
	/* FIXME */
	return null;
    }

    public StringBuilder toXML(XMLMODE mode){

	switch (mode) {
	    
	case DEEP:
	    return XMLHelper.tagged("rating",
				    getReviewReport().toXML(XMLMODE.SHALLOW),
				    // XXX isn't Entity yet: getCriterion().toXML(XMLMODE.SHALLOW),
				    XMLHelper.tagged("grade", grade.toString()),
				    XMLHelper.tagged("comment", comment));
	case SHALLOW: /* FIXME */
	    return XMLHelper.tagged("rating",
				    XMLHelper.tagged("id"));
	default:
	    coma.util.logging.ALogger.log.log(WARN, 
					      "unknown XMLMODE in",
					      this, ':', mode);
	    return null;
	}
    }
    
}
