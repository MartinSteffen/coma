
package coma.handler.db;

import coma.entities.Conference;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.ReviewReport;
import coma.entities.SearchResult;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari</a>
 * Created on Dec 3, 2004  12:20:00 AM
 */

public interface InsertService {
    
    public SearchResult insertPerson(Person person); 
    public SearchResult insertReviewReport(ReviewReport report);
    public SearchResult insertPaper(Paper paper);
    public SearchResult insertConference(Conference conference);

}
