
package coma.handler.db;

import coma.entities.SearchCriteria;
import coma.entities.SearchResult;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari</a>
 * Created on Dec 2, 2004  10:59:59 PM
 */

public interface ReadService {
    
    public SearchResult getConference(SearchCriteria sc);
    public SearchResult getPerson(SearchCriteria sc);
    public SearchResult getPaper(SearchCriteria sc);
    public SearchResult getReviewReport(SearchCriteria sc);
    public SearchResult getRating(SearchCriteria sc);
    public SearchResult getCriterion(SearchCriteria sc);
    public SearchResult getPersonRoles(int conference_id, int person_id);
    public SearchResult isCoAuthorOf(int paper_id, int person_id);
    //public SearchResult getTopic();
    public SearchResult isAboutTopic(int paper_id, int topic_id);
    public SearchResult getPreferedTpoic(int person_id);
    public SearchResult getPreferedPapers(int person_id);
    public SearchResult getDeniedPapers(int person_id);
    public SearchResult getExecludedPapers(int person_id);
    public SearchResult getMessages(int msg_id, int forum_id, int sender_id);
    public SearchResult getForum(int forum_id, int conference_id);
}
