package coma.entities;

import java.util.HashSet;
import java.util.Set;




import static coma.entities.Entity.XMLMODE.*;

import coma.handler.db.ReadService;
import coma.servlet.util.XMLHelper;
import coma.util.logging.ALogger;
import static coma.util.logging.Severity.*;

import static java.util.Arrays.asList;
/**
 *  A Review Report.
 *
 *
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
    public int get_paper_id(){return getPaperId();}

    public int getReviewerId(){return reviewerId;}
    public int get_reviewer_id(){return getReviewerId();}

    public String getSummary(){return summary;}
    public String getRemarks(){return remarks;}
    public String getConfidental(){return confidental;}

    /**
       get the Reviewer who wrote this Report.

       May be null if there is no Reviewer for this Report in the DB,
       or if the DB lookup fails. In either case, a WARNing is logged.

       If there are multiple Reviewers listed, an arbitrary one of
       those is returned. This is a sign of DB inconsistency, and a
       WARNing is logged.
     */
    public Person getReviewer(){
	ReadService rs = new coma.handler.impl.db.ReadServiceImpl();
	SearchCriteria sc = new SearchCriteria();
	sc.setPerson(new Person(getReviewerId()));
	SearchResult sr = rs.getPerson(sc);

	ALogger.log.log(DEBUG, "getReviewer", sr.getResultObj());
	
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
	ALogger.log.log(WARN, 
			"canthappen:",
			"suddenly a set of Persons is empty in", this);
	return null;
    }

    /**
       return the Paper that is reviewed by this ReviewReport.

       May return null if for some reason the DB lookup fails or there
       is no paper for this Report.

       If there are multiple papers referenced (this is a sign of DB
       inconsistency), one of those is returned. Hence, multiple
       consecutive calls may yield different results.

       In all failure cases, a WARNing is logged.
     */
    public Paper getPaper(){
	ReadService rs = new coma.handler.impl.db.ReadServiceImpl();
	SearchCriteria sc = new SearchCriteria();
	sc.setPaper(new Paper(getPaperId()));
	SearchResult sr = rs.getPaper(sc);
	
	ALogger.log.log(DEBUG, "getPaper: ", sr.getResultObj());

	return ((Paper[])sr.getResultObj())[0];
// 	Set<Paper> cs 
// 	    = new java.util.HashSet<Paper>(asList((Paper[]) sr.getResultObj()));

// 	if (cs.size() != 1){
// 	    ALogger.log.log(WARN, 
// 			    "I found multiple papers ",
// 			    cs,
// 			    "for a ReviewReport");
// 	}
// 	for (Paper r: cs){
// 	    return r;
// 	}
// 	ALogger.log.log(WARN, 
// 			"canthappen:",
// 			"suddenly a set of review reports is empty in", this);
// 	return null;
    }

    /**
       Return a set of all ratings that are part of this review report.

       The set returned may be empty, but it is never null.

       Unlike other get-Methods, the result is a proper set even if no
       ratings are found at all. In this case, a WARNing is logged.
     */
    public Set<Rating> getRatings(){
	ALogger.log.log(DEBUG, "in getRatings");
	Set<Rating> result = new HashSet<Rating>();

	ReadService rs = new coma.handler.impl.db.ReadServiceImpl();
	SearchCriteria sc = new SearchCriteria();
	Rating r = new Rating();
	r.setReviewReportId(this.getId());
	sc.setReviewReport(this);
	sc.setRating(r);
	SearchResult sr = rs.getRating(sc);
	
	ALogger.log.log(DEBUG, "getRating:", sr.getResultObj());

	result.addAll(asList((Rating[]) sr.getResultObj()));

	assert (result != null) 
	    : "violates spec: result null";
	return result;
    }

    /**
       returns true iff all Ratings are "valid" in the sense that they
       are in their proper bounds.

       This is a sufficient proof that the Reviewer has handed in this
       report, iff the Ratings are initialized not to be in their
       bounds, like null or 0. Note that existance of the Report as
       such is not.
     */
    public boolean isEdited(){

	ALogger.log.log(DEBUG, "Is it rated?");

	if (((getSummary() != null) && (getSummary().length()>0))
	    || ((getRemarks() != null) && (getRemarks().length()>0))
	    || ((getConfidental() != null) && (getConfidental().length()>0)))
	    return true;

	for (Rating r: getRatings()){
	    Criterion c = r.getCriterion();
	    if ((c == null)
		|| (r.getGrade() < 1)
		|| (r.getGrade() > c.getMaxValue())){
		return false;
	    }
	}
	ALogger.log.log(DEBUG, "Yes, it's rated!");
	return true;

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
				    getPaper().toXML(XMLMODE.SHALLOW),
				    getReviewer().toXML(XMLMODE.SHALLOW),
				    XMLHelper.tagged("summary", getSummary()),
				    XMLHelper.tagged("remarks", getRemarks()),
				    XMLHelper.tagged("confidental", getConfidental()),
				    Entity.manyToXML(getRatings(), XMLMODE.DEEP)
				    );
	  
	default:
	    coma.util.logging.ALogger.log.log(WARN, 
					      "unknown XMLMODE in",
					      this, ':', mode);
	    return null;
	}
    }
    
    public void setConfidental(String c) {confidental = c;}
    public void setId(int i) {id = i;}
    public void setPaperId(int paperId) {this.paperId = paperId;}
    public void set_paper_Id(int p){setPaperId(p);}
    public void setRemarks(String remarks) {this.remarks = remarks;}
    public void setReviewerId(int reviewerId) {this.reviewerId = reviewerId;}
    public void set_reviewer_id(int r){setReviewerId(r);}
    public void setSummary(String summary){this.summary = summary;}
}
