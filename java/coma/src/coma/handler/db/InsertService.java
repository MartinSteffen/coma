package coma.handler.db;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Forum;
import coma.entities.Message;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchResult;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari </a>
 *         Created on Dec 3, 2004 12:20:00 AM
 */

public interface InsertService {

	public SearchResult insertPerson(Person person);

	public SearchResult insertReviewReport(ReviewReport report);

	public SearchResult insertPaper(Paper paper);

	public SearchResult insertConference(Conference conference);

	public SearchResult insertRating(Rating rating);

	public SearchResult insertCriterion(Criterion criterion);

	public SearchResult insertForum(Forum forum);

	public SearchResult insertMessage(Message msg);

	public SearchResult excludesPaper(int person_id, int paper_id);

	public SearchResult deniesPaper(int person_id, int paper_id);

	public SearchResult prefersPaper(int person_id, int paper_id);

	public SearchResult prefersTopic(int person_id, int topic_id);

	public SearchResult insertTopic(int conference_id, String name);

	public SearchResult setPersonRole(int person_id, int conference_id,
			int role_type, int state);

	public SearchResult setCoAuthorOf(int person_id, int paper_id, String name);

	public SearchResult setAboutTopic(int paper_id, int topic_id);

}
