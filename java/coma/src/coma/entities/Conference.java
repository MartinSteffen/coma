package coma.entities;

import java.util.Date;

import coma.entities.Entity.XMLMODE;
import static coma.entities.Entity.XMLMODE.DEEP;
import static coma.entities.Entity.XMLMODE.SHALLOW;
import coma.servlet.util.XMLHelper;
import static coma.util.logging.Severity.WARN;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari</a>
 *
 */
public class Conference extends Entity {

    int id;
    String name;
    String homepage;
    String description;
    Date abstract_submission_deadline;
    Date paper_submission_deadline;
    Date review_deadline;
    Date final_version_deadline;
    Date notification;
    Date conference_start;
    Date conference_end;
    int min_review_per_paper;
    
    public Conference(){
    }

    public Conference(int id){ // ADDED by ums, needed for DB lookup by Criterion
	this.id=id;
    }
    
    public Date getAbstract_submission_deadline() {
        return abstract_submission_deadline;
    }
    public void setAbstract_submission_deadline(
            Date abstract_submission_deadline) {
        this.abstract_submission_deadline = abstract_submission_deadline;
    }
    public Date getConference_end() {
        return conference_end;
    }
    public void setConference_end(Date conference_end) {
        this.conference_end = conference_end;
    }
    public Date getConference_start() {
        return conference_start;
    }
    public void setConference_start(Date conference_start) {
        this.conference_start = conference_start;
    }
    public String getDescription() {
        return description;
    }
    public void setDescription(String description) {
        this.description = description;
    }
    public Date getFinal_version_deadline() {
        return final_version_deadline;
    }
    public void setFinal_version_deadline(Date final_version_deadline) {
        this.final_version_deadline = final_version_deadline;
    }
    public String getHomepage() {
        return homepage;
    }
    public void setHomepage(String homepage) {
        this.homepage = homepage;
    }
    public int getId() {
        return id;
    }
    public void setId(int id) {
        this.id = id;
    }
    public int getMin_review_per_paper() {
        return min_review_per_paper;
    }
    public void setMin_review_per_paper(int min_review_per_paper) {
        this.min_review_per_paper = min_review_per_paper;
    }
    public String getName() {
        return name;
    }
    public void setName(String name) {
        this.name = name;
    }
    public Date getNotification() {
        return notification;
    }
    public void setNotification(Date notification) {
        this.notification = notification;
    }
    public Date getPaper_submission_deadline() {
        return paper_submission_deadline;
    }
    public void setPaper_submission_deadline(Date paper_submission_deadline) {
        this.paper_submission_deadline = paper_submission_deadline;
    }
    public Date getReview_deadline() {
        return review_deadline;
    }
    public void setReview_deadline(Date review_deadline) {
        this.review_deadline = review_deadline;
    }
    
    public StringBuilder toXML(XMLMODE mode)
    {
		switch (mode)
		{
		case DEEP:
		    return XMLHelper.tagged("conference",
				XMLHelper.tagged("id", ""+getId()),
				XMLHelper.tagged("name", getName()),
				XMLHelper.tagged("homepage", getHomepage()),
				XMLHelper.tagged("description", getDescription()),
			    XMLHelper.tagged("abstract", convert_date(getAbstract_submission_deadline())),
			    XMLHelper.tagged("paper", convert_date(getPaper_submission_deadline())),
			    XMLHelper.tagged("review",convert_date(getReview_deadline())),
				XMLHelper.tagged("final", convert_date(getFinal_version_deadline())),
				XMLHelper.tagged("notification", convert_date(getNotification())),
				XMLHelper.tagged("start",convert_date(getConference_start())),
				XMLHelper.tagged("end", convert_date(getConference_end())),
				XMLHelper.tagged("min", getMin_review_per_paper())
					    );
		case SHALLOW:
			 return XMLHelper.tagged("conference",
			 XMLHelper.tagged("id", ""+getId()),
				XMLHelper.tagged("name", getName()),
				XMLHelper.tagged("desc", getDescription())
					    );
		default:
		    coma.util.logging.ALogger.log.log(WARN, 
						      "unknown XMLMODE in",
						      this, ':', mode);
		    return null;
    	}	    
    }
    
    
    public String convert_date(Date date)
    {
    	if (date!=null)
    	{
	    	StringBuffer result=new StringBuffer();
	    	String sqlDate = date.toString();
	    	result.append(sqlDate.charAt(8));
	    	result.append(sqlDate.charAt(9));
	    	result.append(".");
	    	result.append(sqlDate.charAt(5));
	    	result.append(sqlDate.charAt(6));
	    	result.append(".");
	    	result.append(sqlDate.charAt(0));
	    	result.append(sqlDate.charAt(1));
	    	result.append(sqlDate.charAt(2));
	    	result.append(sqlDate.charAt(3));
	    	return result.toString();
    	}
    	else
    		return null;
    	
    }
    
    public String toXML(){
    	
    	return null;
    }
}
