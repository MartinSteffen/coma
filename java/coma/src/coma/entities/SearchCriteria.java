
package coma.entities;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari</a>
 * Created on Dec 2, 2004  11:03:07 PM
 * 
 * <p>
 * The properties of this class enables you to create a search
 * criterion to get some data from the database.
 * <br>
 * Examples:
 * <br>
 * To search for a certain person you just create 
 * a new instance 'person' and set some properties of this object
 * that can be used to search when quering the database.
 * If you should search for all rejecte papers, create a new instance
 * 'Paper' and set Paper.state to 'rejected'
 * </p>
 */

public class SearchCriteria {
    
    public Conference conference;
    public Forum forum;
    public Paper paper;
    public Person person;
    public ReviewReport reviewReport;
    
    public SearchCriteria(){
    }
    
    
    public Conference getConference() {
        return conference;
    }
    public void setConference(Conference conference) {
        this.conference = conference;
    }
    public Forum getForum() {
        return forum;
    }
    public void setForum(Forum forum) {
        this.forum = forum;
    }
    public Paper getPaper() {
        return paper;
    }
    public void setPaper(Paper paper) {
        this.paper = paper;
    }
    public Person getPerson() {
        return person;
    }
    public void setPerson(Person person) {
        this.person = person;
    }
  
    public ReviewReport getReviewReport() {
        return reviewReport;
    }
    public void setReviewReport(ReviewReport reviewReport) {
        this.reviewReport = reviewReport;
    }
}
