package coma.handler.impl.db;

import java.sql.Connection;
import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Vector;

import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchResult;
import coma.entities.Topic;
import coma.handler.db.UpdateService;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari </a>
 *         Created on Dec 2, 2004 11:25:45 PM
 */

public class UpdateServiceImpl extends Service implements UpdateService {


	public UpdateServiceImpl() {
	}

	public SearchResult updatePerson(Person person) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Connection conn = null;
		boolean ok = true;
		if (person == null) {
			ok = false;
			info.append("ERROR: person must not be null\n");
		}
		if (ok && person.getId() <= 0 && person.getEmail() == null) {
			ok = false;
			info.append("ERROR: person[id || email] must not be null");
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				info
						.append("ERROR: Coma could not establish a connection to the database");
				log.error(e.toString() + "\n");
			}
		}
		if (ok) {
			try {
				String UPDATE_QUERY = "UPDATE Person SET "
						+ "first_name = ?, last_name = ?, title = ?, affiliation = ?,"
						+ "phone_number = ?, fax_number = ?, street = ?,"
						+ "postal_code = ?, city = ?, state = ?, country = ? "
						+ " WHERE id = " + person.getId() + " OR Email = '"
						+ person.getEmail() + "'";
				int pstmtCounter = 0;
				conn.setAutoCommit(false);
				PreparedStatement pstmt = conn.prepareStatement(UPDATE_QUERY);
				pstmt.setString(++pstmtCounter, person.getFirst_name());
				pstmt.setString(++pstmtCounter, person.getLast_name());
				pstmt.setString(++pstmtCounter, person.getTitle());
				pstmt.setString(++pstmtCounter, person.getAffiliation());
				pstmt.setString(++pstmtCounter, person.getPhone_number());
				pstmt.setString(++pstmtCounter, person.getFax_number());
				pstmt.setString(++pstmtCounter, person.getStreet());
				pstmt.setString(++pstmtCounter, person.getPostal_code());
				pstmt.setString(++pstmtCounter, person.getCity());
				pstmt.setString(++pstmtCounter, person.getState());
				pstmt.setString(++pstmtCounter, person.getCountry());

				int affRows = pstmt.executeUpdate();
				if (affRows <= 0) {
					info.append("ERROR: Dataset could not be updated\n");
					conn.rollback();
					result.SUCCESS = false;
				}else{
					result.SUCCESS = true;
				}
			} catch (SQLException e) {
				log.error(e);
				info.append("ERROR: " + e.toString() + "\n");
			} finally {
				if (conn != null) {
					try {
						conn.setAutoCommit(true);
						conn.close();
						conn = null;
					} catch (SQLException e1) {
						log.error(e1.toString());
					}
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}

	public SearchResult updateReviewReport(ReviewReport report) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Connection conn = null;
		boolean ok = true;
		if (report == null) {
			ok = false;
			info.append("ERROR: Report must not be null\n");
		}
		if (ok && (report.getId() < 0)) {
			ok = false;
			info.append("ERROR: report[id] must not be less than 0");
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				info
						.append("ERROR: Coma could not establish a connection to the database");
				log.error(e.toString() + "\n");
			}
		}
		if (ok) {
			try {
				String UPDATE_QUERY = "UPDATE ReviewReport SET "
				    + " summary = ?, remarks = ?, confidential = ?"
				    + " WHERE id = " + report.getId();
				int pstmtCounter = 0;
				PreparedStatement pstmt = conn.prepareStatement(UPDATE_QUERY);
				pstmt.setString(++pstmtCounter, report.getSummary());
				pstmt.setString(++pstmtCounter, report.getRemarks());
				pstmt.setString(++pstmtCounter, report.getConfidental());

				int affRows = pstmt.executeUpdate();
				result.setSUCCESS(true);
				pstmt.close();
				if (affRows != 1 && ok) {
					info.append("ERROR: Dataset could not be updated\n");
					conn.rollback();
					result.setSUCCESS(false);
				}
			} catch (SQLException e) {
				info.append("ERROR: " + e.toString() + "\n");
				result.setSUCCESS(false);
			} finally {
				if (conn != null) {
					try {
						conn.close();
						conn = null;
					} catch (SQLException e1) {
						log.error(e1.toString());
					}
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}

	public SearchResult updatePaper(Paper paper) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Connection conn = null;
		boolean ok = true;
		if (paper == null) {
			ok = false;
			info.append("ERROR: paper must not be null\n");
		}
		if (ok && (paper.getId() < 0)) {
			ok = false;
			info.append("ERROR: paper[id] must not be less than 0");
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				info
						.append("ERROR: Coma could not establish a connection to the database");
				log.error(e.toString() + "\n");
			}
		}
		if (ok) {
			try {
				String UPDATE_QUERY = "UPDATE Paper SET "
						+ " author_id = ? , title = ?, abstract = ?,"
						+ " filename = ?, state = ?, mime_type = ?,"
						+ " version = ?"
						+ " WHERE id = " + paper.getId();
				int pstmtCounter = 0;
				PreparedStatement pstmt = conn.prepareStatement(UPDATE_QUERY);
				pstmt.setInt(++pstmtCounter, paper.getAuthor_id());
				pstmt.setString(++pstmtCounter, paper.getTitle());
				pstmt.setString(++pstmtCounter, paper.getAbstract());
				pstmt.setString(++pstmtCounter, paper.getFilename());
				pstmt.setInt(++pstmtCounter, paper.getState());
				pstmt.setString(++pstmtCounter, paper.getMim_type());
				pstmt.setInt(++pstmtCounter, paper.getVersion());
				int affRows = pstmt.executeUpdate();
				pstmt.close();
				if (affRows != 1 && ok) {
					info.append("ERROR: Dataset could not be updated\n");
					conn.rollback();
				} else {
					Vector<Topic> topics = paper.getTopics();
					if (topics != null) {
						conn.setAutoCommit(true);
						UPDATE_QUERY = " DELETE FROM IsAboutTopic "
							+ " WHERE paper_id = "+paper.getId();
						Statement stmt = conn.createStatement();
						int rows = stmt.executeUpdate(UPDATE_QUERY);
						if (rows < 1) {
							info
									.append("Error: Could not delete topics for  Paper!");
							conn.rollback();
						}
						InsertServiceImpl insert = new InsertServiceImpl();
						for (int i = 0; i < topics.size(); i++) {
							insert.setAboutTopic(paper.getId(), ((Topic)topics.elementAt(i)).getId());
						}
					}
				    result.setSUCCESS(true);
				}
			} catch (SQLException e) {
				info.append("ERROR: " + e.toString() + "\n");
			} finally {
				if (conn != null) {
					try {
						conn.close();
						conn = null;
					} catch (SQLException e1) {
						log.error(e1.toString());
					}
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}

	public SearchResult updateConference(Conference conference) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Connection conn = null;
		boolean ok = true;
		if (conference == null) {
			ok = false;
			info.append("ERROR: conference must not be null\n");
		}
		if (ok && (conference.getId() < 0)) {
			ok = false;
			info.append("ERROR: conference[id] must not be less than 0");
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				info
						.append("ERROR: Coma could not establish a connection to the database");
				log.error(e.toString() + "\n");
			}
		}
		if (ok) {
			try {
				String UPDATE_QUERY = "UPDATE Conference SET "
						+ "name = ?,homepage = ?,description = ?,"
						+ "abstract_submission_deadline = ?,paper_submission_deadline = ?,"
						+ "review_deadline = ?,final_version_deadline = ?,notification = ?,"
						+ "conference_start = ?,conference_end = ?,min_reviews_per_paper = ?"
						+ " WHERE id = " + conference.getId();
				int pstmtCounter = 0;
				PreparedStatement pstmt = conn.prepareStatement(UPDATE_QUERY);
				pstmt.setString(++pstmtCounter, conference.getName());
				pstmt.setString(++pstmtCounter, conference.getHomepage());
				pstmt.setString(++pstmtCounter, conference.getDescription());
				if (conference.getAbstract_submission_deadline() != null) {
					pstmt.setDate(++pstmtCounter, new Date(conference
							.getAbstract_submission_deadline().getTime()));
				} else {
					pstmt.setString(++pstmtCounter, null);
				}
				if (conference.getPaper_submission_deadline() != null) {
					pstmt.setDate(++pstmtCounter, new Date(conference
							.getPaper_submission_deadline().getTime()));
				} else {
					pstmt.setString(++pstmtCounter, null);
				}
				if (conference.getReview_deadline() != null) {
					pstmt.setDate(++pstmtCounter, new Date(conference
							.getReview_deadline().getTime()));
				} else {
					pstmt.setString(++pstmtCounter, null);
				}
				if (conference.getFinal_version_deadline() != null) {
					pstmt.setDate(++pstmtCounter, new Date(conference
							.getFinal_version_deadline().getTime()));
				} else {
					pstmt.setString(++pstmtCounter, null);
				}
				if (conference.getNotification() != null) {
					pstmt.setDate(++pstmtCounter, new Date(conference
							.getNotification().getTime()));
				} else {
					pstmt.setString(++pstmtCounter, null);
				}
				if (conference.getConference_start() != null) {
					pstmt.setDate(++pstmtCounter, new Date(conference
							.getConference_start().getTime()));
				} else {
					pstmt.setString(++pstmtCounter, null);
				}
				if (conference.getConference_end() != null) {
					pstmt.setDate(++pstmtCounter, new Date(conference
							.getConference_end().getTime()));
				} else {
					pstmt.setString(++pstmtCounter, null);
				}
				pstmt.setInt(++pstmtCounter, conference
						.getMin_review_per_paper());

				int affRows = pstmt.executeUpdate();
				pstmt.close();
				if (affRows != 1 && ok) {
					info.append("ERROR: Dataset could not be updated\n");
					conn.rollback();
				}
			} catch (SQLException e) {
				info.append("ERROR: " + e.toString() + "\n");
			} finally {
				if (conn != null) {
					try {
						conn.close();
						conn = null;
					} catch (SQLException e1) {
						log.error(e1.toString());
					}
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	} /*
		 * (non-Javadoc)
		 * 
		 * @see coma.handler.db.UpdateService#updateRating(coma.entities.Rating)
		 */

	public SearchResult updateRating(Rating rating) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Connection conn = null;
		boolean ok = true;
		if (rating == null) {
			ok = false;
			info.append("ERROR: rating must not be null\n");
		}
		if (ok && (rating.get_criterion_id() <= 0)) {
			ok = false;
			info.append("ERROR: rating[criterion_id] must not be 0");
		}
		if (ok && (rating.get_review_id() <= 0)) {
			ok = false;
			info.append("ERROR: rating[review_id] must not be 0");
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				info
						.append("ERROR: Coma could not establish a connection to the database");
				log.error(e.toString() + "\n");
			}
		}
		if (ok) {
			try {
				String UPDATE_QUERY = "UPDATE Rating SET " 
				    /*+ " review_id ="
						+ rating.get_review_id() + " ,criterion_id ="
						+ rating.getCriterionId() + " grade = "
						+ rating.getGrade() + " ,comment = "
						+ rating.getComment() + " WHERE review_id = "
						+ rating.get_review_id() + "  AND criterion_id = "
						+ rating.get_criterion_id();
						*/
				    + "grade = ?,"
				    + "comment = ? "
				    + " WHERE review_id = "+rating.get_review_id()
				    + " AND criterion_id = "+rating.get_criterion_id();
				int pstmtCounter = 0;
				PreparedStatement pstmt = conn.prepareStatement(UPDATE_QUERY);
				pstmt.setInt(++pstmtCounter, rating.getGrade());
				pstmt.setString(++pstmtCounter, rating.getComment());
// 				Statement stmt = conn.createStatement();

// 				int affRows = stmt.executeUpdate(UPDATE_QUERY);
				int affRows = pstmt.executeUpdate();
				result.setSUCCESS(true);
				pstmt.close();
				
				if (affRows != 1 && ok) {
					info.append("ERROR: Dataset could not be updated:"+affRows+"\n");
					conn.rollback();
				}
			} catch (SQLException e) {
				info.append("ERROR: " + e.toString() + "\n");
				result.setSUCCESS(false);
			} finally {
				if (conn != null) {
					try {
						conn.close();
						conn = null;
					} catch (SQLException e1) {
						log.error(e1.toString());
					}
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see coma.handler.db.UpdateService#updateCriterion(coma.entities.Criterion)
	 */
	public SearchResult updateCriterion(Criterion criterion) 
	{
		final String QUERY = "UPDATE Criterion SET id = "+criterion.getId()+
		",conference_id = "+criterion.getConferenceId()+",name = "+criterion.getName()+
		",description = "+ criterion.getDescription()+"max_value = "+criterion.get_max_value()+
		",quality_rating = "+criterion.get_quality_rating();
		return executeQuery(QUERY);
	}

}
