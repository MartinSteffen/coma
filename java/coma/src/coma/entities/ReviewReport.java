/*
 * Created on Nov 28, 2004
 *
 * TODO To change the template for this generated file go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
package coma.entities;

import java.util.*;

/**
 * @author ziad, ums
 *
 * TODO To change the template for this generated type comment go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
public class ReviewReport {

    private Integer id;
    private Integer paperId;
    private Integer reviewerId;
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
}
