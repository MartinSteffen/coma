package coma.entities;

/**
   Wrapper class for the DB Rating entries.

 */
public class Rating {

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
    
}
