
package coma.handler.db;

import coma.entities.SearchCriteria;
import coma.entities.SearchResult;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari</a>
 * Created on Dec 2, 2004  10:59:59 PM
 */

public interface ReadService {
    
   public SearchResult getConference(SearchCriteria sc);
   public SearchResult getPerson(SearchCriteria sc);
   public SearchResult getPaper(SearchCriteria sc);
   public SearchResult getReviewReport(SearchCriteria sc);
   
}
