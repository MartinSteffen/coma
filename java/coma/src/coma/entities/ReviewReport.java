/*
 * Created on Nov 28, 2004
 *
 * TODO To change the template for this generated file go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
package coma.entities;

/**
 * @author ziad, ums
 *
 * TODO To change the template for this generated type comment go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
public class ReviewReport {

    private final int id;
    private final int paperId;
    private final int reviewerId;
    private String summary;
    private String remarks;
    private String confidental;

    public ReviewReport(int id){
	
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
}
