package coma.handler.db;

import coma.entities.SearchResult;

/**
 * Created on 19.01.2005
 * <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari</a>
 */

public interface DeleteService {
	
	public SearchResult deleteConference(int conference_id);
	public SearchResult deletePerson(int person_id);
	public SearchResult deletePaper(int paper_id);
	public SearchResult deleteCriterion(int criterion_id);
	public SearchResult deleteReviewReport(int report_id);
	public SearchResult deleteRating(int reviewreport_id);
	public SearchResult deleteTopic(int topic_id);
	public SearchResult deleteReviewReportByReviewerId(int reviewer_id, int paper_id);
}
