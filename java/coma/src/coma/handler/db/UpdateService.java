
package coma.handler.db;

import coma.entities.Conference;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.ReviewReport;
import coma.entities.SearchResult;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari</a>
 * Created on Dec 2, 2004  11:22:11 PM
 */

public interface UpdateService {
    
    public SearchResult updatePerson(Person person); 
    public SearchResult updateReviewReport(ReviewReport report);
    public SearchResult updatePaper(Paper paper);
    public SearchResult updateConference(Conference conference);
}
