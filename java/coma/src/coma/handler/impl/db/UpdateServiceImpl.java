package coma.handler.impl.db;

import java.sql.Connection;
import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Statement;

import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchResult;
import coma.handler.db.UpdateService;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari </a>
 *         Created on Dec 2, 2004 11:25:45 PM
 */

public class UpdateServiceImpl extends Service implements UpdateService {

	private static final Category log = Category
			.getInstance(UpdateServiceImpl.class.getName());

	public UpdateServiceImpl() {
		// super.init();
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
				System.out.println(e.toString() + "\n");
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
				System.out.println(e);
				info.append("ERROR: " + e.toString() + "\n");
			} finally {
				if (conn != null) {
					try {
						conn.setAutoCommit(true);
						conn.close();
						conn = null;
					} catch (SQLException e1) {
						System.out.println(e1.toString());
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
				System.out.println(e.toString() + "\n");
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
						System.out.println(e1.toString());
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
				System.out.println(e.toString() + "\n");
			}
		}
		if (ok) {
			try {
				String UPDATE_QUERY = "UPDATE Paper SET "
						+ " author_id ? , title = ?, abstract = ?,"
						+ " filename = ?, state = ?, mime_typen = ?"
						+ " WHERE id = " + paper.getId();
				int pstmtCounter = 0;
				PreparedStatement pstmt = conn.prepareStatement(UPDATE_QUERY);
				pstmt.setInt(++pstmtCounter, paper.getAuthor_id());
				pstmt.setString(++pstmtCounter, paper.getTitle());
				pstmt.setString(++pstmtCounter, paper.getAbstract());
				pstmt.setString(++pstmtCounter, paper.getFilename());
				pstmt.setInt(++pstmtCounter, paper.getState());
				pstmt.setString(++pstmtCounter, paper.getMim_type());

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
						System.out.println(e1.toString());
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
				System.out.println(e.toString() + "\n");
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
						System.out.println(e1.toString());
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
				System.out.println(e.toString() + "\n");
			}
		}
		if (ok) {
			try {
				String UPDATE_QUERY = "UPDATE Paper SET " + " review_id ="
						+ rating.get_review_id() + " ,criterion_id ="
						+ rating.getCriterionId() + " ,grade = "
						+ rating.getGrade() + " ,comment = "
						+ rating.getComment() + " WHERE review_id = "
						+ rating.get_review_id() + "  AND criterion_id = "
						+ rating.get_criterion_id();
				int pstmtCounter = 0;
				Statement stmt = conn.createStatement();

				int affRows = stmt.executeUpdate(UPDATE_QUERY);
				stmt.close();
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
						System.out.println(e1.toString());
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
	public SearchResult updateCriterion(Criterion criterion) {
		// TODO Auto-generated method stub
		return null;
	}

}
