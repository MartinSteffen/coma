package coma.handler.impl.db;

import coma.entities.SearchResult;
import coma.handler.db.DeleteService;

/**
 * Created on 19.01.2005 
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari </a>
 */

public class DeleteServiceImpl extends Service implements DeleteService {

	/*
	 * (non-Javadoc)
	 * 
	 * @see coma.handler.db.DeleteService#deleteConference(int)
	 */
	public SearchResult deleteConference(int conference_id) {
		final String QUERY = "DELETE FROM Conference WHERE id ="+conference_id; 
		return executeQuery(QUERY);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see coma.handler.db.DeleteService#deletePerson(int)
	 */
	public SearchResult deletePerson(int person_id) {
		final String QUERY = "DELETE FROM Person WHERE id ="+person_id; 
		return executeQuery(QUERY);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see coma.handler.db.DeleteService#deletePaper(int)
	 */
	public SearchResult deletePaper(int paper_id) {
		final String QUERY = "DELETE FROM Paper WHERE id ="+paper_id; 
		return executeQuery(QUERY);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see coma.handler.db.DeleteService#deleteCriterion(int)
	 */
	public SearchResult deleteCriterion(int criterion_id) {
		final String QUERY = "DELETE FROM Criterion WHERE id ="+criterion_id; 
		return executeQuery(QUERY);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see coma.handler.db.DeleteService#deleteReviewReport(int)
	 */
	public SearchResult deleteReviewReport(int report_id) {
		final String QUERY = "DELETE FROM ReviewReport WHERE id ="+report_id; 
		return executeQuery(QUERY);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see coma.handler.db.DeleteService#deleteTopic(int)
	 */
	public SearchResult deleteTopic(int topic_id) {
		final String QUERY = "DELETE FROM Topic WHERE id ="+topic_id; 
		return executeQuery(QUERY);
	}
	
	public SearchResult deleteRating(int reviewer_id,int criterion_id) {
		final String QUERY = "DELETE FROM Rating WHERE review_id = "+reviewer_id+" AND criterion_id = "+criterion_id ; 
		return executeQuery(QUERY);
	}
	
	public SearchResult deletePreferedTopic(int person_id, int topic_id) {
		String QUERY = "DELETE FROM  PrefersTopic WHERE Person_id = "+person_id
			+ " AND topic_id = "+ topic_id;
		return executeQuery(QUERY);
	}
	public SearchResult deleteRating(int reviewreport_id) {
		String QUERY = "DELETE FROM Rating WHERE review_id  = "+reviewreport_id;
		return executeQuery(QUERY);
	}
	public SearchResult deleteReviewReportByReviewerId(int reviewer_id){
		String QUERY = "DELETE FROM ReviewReport WHERE reviewer_id = "+reviewer_id;
		return executeQuery(QUERY);
	}
}
