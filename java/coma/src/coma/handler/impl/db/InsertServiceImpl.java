package coma.handler.impl.db;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Date;
import java.util.Vector;

import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Forum;
import coma.entities.Message;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchResult;
import coma.entities.Topic;
import coma.handler.db.InsertService;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari </a>
 *         Created on Dec 3, 2004 12:21:37 AM
 */

public class InsertServiceImpl extends Service implements InsertService {

	public InsertServiceImpl() {
	}

	/**
	 * @see coma.handler.db.InsertService#insertPerson(coma.entities.Person)
	 */
	public SearchResult insertPerson(Person person) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;
		if (person == null) {
			info.append("ERROR: person must not be null\n");
			ok = false;
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				info
						.append("Coma could not establish a connection to the database\n");
				info.append(e.toString());
			}
		}
		if (ok) {
			String INSERT_QUERY = "INSERT INTO Person "
					+ "(first_name, last_name, title, affiliation,"
					+ "email, phone_number, fax_number, street,"
					+ "postal_code, city, state, country, password)"
					+ "VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";

			try {
				conn.setAutoCommit(false);
				PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
				int pstmtCount = 0;
				pstmt.setString(++pstmtCount, person.getFirst_name());
				pstmt.setString(++pstmtCount, person.getLast_name());
				pstmt.setString(++pstmtCount, person.getTitle());
				pstmt.setString(++pstmtCount, person.getAffiliation());
				pstmt.setString(++pstmtCount, person.getEmail());
				pstmt.setString(++pstmtCount, person.getPhone_number());
				pstmt.setString(++pstmtCount, person.getFax_number());
				pstmt.setString(++pstmtCount, person.getStreet());
				pstmt.setString(++pstmtCount, person.getPostal_code());
				pstmt.setString(++pstmtCount, person.getCity());
				pstmt.setString(++pstmtCount, person.getState());
				pstmt.setString(++pstmtCount, person.getCountry());
				pstmt.setString(++pstmtCount, person.getPassword());

				int rows = pstmt.executeUpdate();
				ResultSet keyRes = pstmt.getGeneratedKeys();
				int person_id = 0;
				if (keyRes.next())
					person_id = keyRes.getInt(1);

				if (rows != 1) {
					conn.rollback();
					info
							.append("Error: Person could not inserted into the database\n");
				} else {
					String QUERY = "INSERT INTO Role VALUES(?,?,?,?)";
					int role[] = person.getRole_type();
					if (role != null) {
						for (int i = 0; i < role.length; i++) {
							pstmt = conn.prepareStatement(QUERY);
							pstmt.setInt(1, person.getConference_id());
							pstmt.setInt(2, person_id);
							pstmt.setInt(3, role[i]);
							pstmt.setString(4, null);

							rows = pstmt.executeUpdate();
							if (rows != 1) {
								info
										.append("Error: could not set the roles nummber ("
												+ role[i]
												+ ") for this Person!");
								conn.rollback();
							}
						}
					}
					result.setSUCCESS(true);
					info.append("Person inserted successfully\n");
				}
			} catch (SQLException e) {
				info.append(e.toString());
				log.error(e.toString());

			} finally {
				try {
					if (conn != null) {
						conn.setAutoCommit(true);
						conn.close();
					}
				} catch (Exception e) {
					info
							.append("ERROR: clould not close database connection\n");
					;
					info.append(e.toString());
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}

	/**
	 * @see coma.handler.db.InsertService#insertReviewReport(coma.entities.ReviewReport)
	 */
	public SearchResult insertReviewReport(ReviewReport report) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;
		if (report == null) {
			info.append("ERROR: report must not be null\n");
			ok = false;
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				info
						.append("Coma could not establish a connection to the database\n");
				log.error(e);
			}
		}
		if (ok) {
			String INSERT_QUERY = "INSERT INTO ReviewReport "
					+ "(paper_id, reviewer_id, summary, remarks,"
					+ "confidential) " + "VALUES(?,?,?,?,?)";

			try {
				conn.setAutoCommit(false);
				PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
				pstmt.setInt(1, report.get_paper_id());
				pstmt.setInt(2, report.getReviewerId());
				pstmt.setString(3, report.getSummary());
				pstmt.setString(4, report.getRemarks());
				pstmt.setString(5, report.getConfidental());

				int rows = pstmt.executeUpdate();
				if (rows != 1) {
					conn.rollback();
					info
							.append("ReviewReport could not inserted into the database\n");
				} else {
					result.setSUCCESS(true);
					info.append("ReviewReport inserted successfully\n");
				}
			} catch (SQLException e) {
				// info.append(e.toString());

				log.error(e);
			} finally {
				try {
					if (conn != null) {
						conn.setAutoCommit(true);
						conn.close();
					}
				} catch (Exception e) {
					info
							.append("ERROR: clould not close database connection\n");

					// info.append(e.toString());
					log.error(e);
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}

	/**
	 * @see coma.handler.db.InsertService#insertPaper(coma.entities.Paper)
	 */
	public SearchResult insertPaper(Paper paper) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;
		if (paper == null) {
			info.append("ERROR: paper must not be null\n");
			ok = false;
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				info
						.append("Coma could not establish a connection to the database\n");
				log.error(e);
			}
		}
		if (ok) {
			String INSERT_QUERY = "INSERT INTO Paper "
					+ " (conference_id, author_id, title, abstract,"
					+ "last_edited, version,filename,state,mime_type) "
					+ " VALUES(?,?,?,?,?,?,?, ?, ?)";

			try {
				conn.setAutoCommit(false);
				PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
				int counter = 0;
				pstmt.setInt(++counter, paper.getConference_id());
				pstmt.setInt(++counter, paper.getAuthor_id());
				pstmt.setString(++counter, paper.getTitle());
				pstmt.setString(++counter, paper.getAbstract());
				if (paper.getLast_edited() != null) {
					pstmt.setDate(++counter, new java.sql.Date(paper
							.getLast_edited().getTime()));
				} else {
					pstmt.setDate(++counter, new java.sql.Date(new Date()
							.getTime()));
				}
				pstmt.setInt(++counter, paper.getVersion());
				pstmt.setString(++counter, paper.getFilename());
				pstmt.setInt(++counter, paper.getState());
				pstmt.setString(++counter, paper.getMim_type());

				int rows = pstmt.executeUpdate();
				ResultSet keyRes = pstmt.getGeneratedKeys();
				int paper_id = 0;
				if (keyRes.next())
					paper_id = keyRes.getInt(1);
				if (rows != 1) {
					conn.rollback();
					info.append("Paper could not inserted into the database\n");
				} else {

					Vector<Topic> topics = paper.getTopics();
					if (topics != null) {
						conn.setAutoCommit(true);
						for (int i = 0; i < topics.size(); i++) {
							SearchResult sr = setAboutTopic(paper_id, topics.elementAt(i).getId());
							if (!sr.SUCCESS) {
								info
										.append("Error: could not insert Topic nummber ("
												+i+ ") for this Paper!");
								conn.rollback();
							}
						}
					}
					result.setSUCCESS(true);
					info.append("Paper inserted successfully\n");
				}
				pstmt.close();
			} catch (SQLException e) {
				info.append(e.toString());
				log.error(e);
			} finally {
				try {
					if (conn != null) {
						conn.setAutoCommit(true);
						conn.close();
					}
				} catch (Exception e) {
					info
							.append("ERROR: clould not close database connection\n");

					info.append(e.toString());
					log.error(e);
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}

	/**
	 * @see coma.handler.db.InsertService#insertConference(coma.entities.Conference)
	 */
	public SearchResult insertConference(Conference conference) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;
		if (conference == null) {
			info.append("ERROR: conference must not be null\n");
			ok = false;
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				System.out.println(e);
				info
						.append("Coma could not establish a connection to the database\n");
				info.append(e.toString());
			}
		}
		if (ok) {
			String INSERT_QUERY = "INSERT INTO Conference "
					+ "(name,homepage,description,abstract_submission_deadline,"
					+ "paper_submission_deadline,review_deadline,"
					+ "final_version_deadline,notification,conference_start,"
					+ "conference_end,min_reviews_per_paper) "
					+ "VALUES(?,?,?,?,?,?,?,?,?,?,?)";

			try {
				conn.setAutoCommit(false);
				PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
				int pstmtCounter = 0;
				pstmt.setString(++pstmtCounter, conference.getName());
				pstmt.setString(++pstmtCounter, conference.getHomepage());
				pstmt.setString(++pstmtCounter, conference.getDescription());
				if (conference.getAbstract_submission_deadline() != null) {
					pstmt.setDate(++pstmtCounter, new java.sql.Date(conference
							.getAbstract_submission_deadline().getTime()));
				} else {
					pstmt.setDate(++pstmtCounter, null);
				}
				if (conference.getPaper_submission_deadline() != null) {
					pstmt.setDate(++pstmtCounter, new java.sql.Date(conference
							.getPaper_submission_deadline().getTime()));
				} else {
					pstmt.setDate(++pstmtCounter, null);
				}
				if (conference.getReview_deadline() != null) {
					pstmt.setDate(++pstmtCounter, new java.sql.Date(conference
							.getReview_deadline().getTime()));
				} else {
					pstmt.setDate(++pstmtCounter, null);
				}
				if (conference.getFinal_version_deadline() != null) {
					pstmt.setDate(++pstmtCounter, new java.sql.Date(conference
							.getFinal_version_deadline().getTime()));
				} else {
					pstmt.setDate(++pstmtCounter, null);
				}
				if (conference.getNotification() != null) {
					pstmt.setDate(++pstmtCounter, new java.sql.Date(conference
							.getNotification().getTime()));
				} else {
					pstmt.setDate(++pstmtCounter, null);
				}
				if (conference.getConference_start() != null) {
					pstmt.setDate(++pstmtCounter, new java.sql.Date(conference
							.getConference_start().getTime()));
				} else {
					pstmt.setDate(++pstmtCounter, null);
				}
				if (conference.getConference_end() != null) {
					pstmt.setDate(++pstmtCounter, new java.sql.Date(conference
							.getConference_end().getTime()));
				} else {
					pstmt.setDate(++pstmtCounter, null);
				}
				pstmt.setInt(++pstmtCounter, conference
						.getMin_review_per_paper());

				int rows = pstmt.executeUpdate();
				if (rows != 1) {
					conn.rollback();
					info
							.append("counference could not inserted into the database\n");
				} else {
					result.setSUCCESS(true);
					info.append("conference inserted successfully\n");
				}
			} catch (SQLException e) {
				info.append(e.toString());
				log.error(e);
			} finally {
				try {
					if (conn != null) {
						conn.setAutoCommit(true);
						conn.close();
					}
				} catch (Exception e) {
					info
							.append("ERROR: clould not close database connection\n");
					;
					info.append(e.toString());
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}

	/**
	 * @see coma.handler.db.InsertService#insertRating(coma.entities.Rating)
	 */
	public SearchResult insertRating(Rating rating) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;
		if (rating == null) {
			info.append("ERROR: rating must not be null\n");
			ok = false;
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				info
						.append("Coma could not establish a connection to the database\n");
				log.error(e);
			}
		}
		if (ok) {
			String INSERT_QUERY = "INSERT INTO Rating "
					+ "(review_id,criterion_id,grade,comment) "
					+ "VALUES(?,?,?,?)";

			try {
				conn.setAutoCommit(false);
				PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
				pstmt.setInt(1, rating.getReviewReportId());
				pstmt.setInt(2, rating.get_criterion_id());
				pstmt.setInt(3, rating.getGrade());
				pstmt.setString(4, rating.getComment());

				int rows = pstmt.executeUpdate();
				if (rows != 1) {
					conn.rollback();
					info
							.append("rating could not inserted into the database\n");
				} else {
					result.setSUCCESS(true);
					info.append("rerson inserted successfully\n");
				}
			} catch (SQLException e) {
				// info.append(e.toString());
				log.error(e);
			} finally {
				try {
					if (conn != null) {
						conn.setAutoCommit(true);
						conn.close();
					}
				} catch (Exception e) {
					info
							.append("ERROR: clould not close database connection\n");

					// info.append(e.toString());
					log.error(e);
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}

	/**
	 * @see coma.handler.db.InsertService#insertCriterion(coma.entities.Criterion)
	 */
	public SearchResult insertCriterion(Criterion criterion) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;
		if (criterion == null) {
			info.append("ERROR: criterion must not be null\n");
			ok = false;
		}
		if (ok) {
			try {
				conn = getConnection();
			} catch (Exception e) {
				ok = false;
				info
						.append("Coma could not establish a connection to the database\n");
				log.error(e);
			}
		}
		if (ok) {
			String INSERT_QUERY = "INSERT INTO Criterion "
					+ "(conference_id,name,description,max_value,quality_rating) "
					+ "VALUES(?,?,?,?,?)";

			try {
				conn.setAutoCommit(false);
				PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
				pstmt.setInt(1, criterion.getConferenceId());
				pstmt.setString(2, criterion.getName());
				pstmt.setString(3, criterion.getDescription());
				pstmt.setInt(4, criterion.getMaxValue());
				pstmt.setInt(5, criterion.getQualityRating());

				int rows = pstmt.executeUpdate();

				if (rows != 1) {
					conn.rollback();
					info
							.append("criterion could not inserted into the database\n");
				} else {
					result.setSUCCESS(true);
					info.append("criterion inserted successfully\n");
				}
			} catch (SQLException e) {
				info.append(e.toString());
				log.error(e);
			} finally {
				try {
					if (conn != null) {
						conn.setAutoCommit(true);
						conn.close();
					}
				} catch (Exception e) {
					info
							.append("ERROR: clould not close database connection\n");

					info.append(e.toString());
					log.error(e);
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}

	/**
	 * @see coma.handler.db.InsertService#insertForum(coma.entities.Forum)
	 */
	public SearchResult insertForum(Forum forum) {
		// TODO
		return null;
	}

	/**
	 * @see coma.handler.db.InsertService#insertMessage(coma.entities.Message)
	 */
	public SearchResult insertMessage(Message msg) {
		// TODO
		return null;
	}

	public SearchResult excludesPaper(int person_id, int paper_id) {
		String QUERY = "INSERT INTO ExcludesPaper " + " VALUES(" + person_id
				+ "," + paper_id + ")";
		return executeQuery(QUERY);
	}

	public SearchResult deniesPaper(int person_id, int paper_id) {
		String QUERY = "INSERT INTO DeniesPaper " + " VALUES(" + person_id
				+ "," + paper_id + ")";
		return executeQuery(QUERY);
	}

	public SearchResult prefersPaper(int person_id, int paper_id) {
		String QUERY = "INSERT INTO PrefersPaper " + " VALUES(" + person_id
				+ "," + paper_id + ")";
		return executeQuery(QUERY);
	}

	public SearchResult prefersTopic(int person_id, int topic_id) {
		String QUERY = "INSERT INTO PrefersTopic " + " VALUES(" + person_id
				+ "," + topic_id + ")";
		return executeQuery(QUERY);
	}

	public SearchResult setAboutTopic(int paper_id, int topic_id) {
		String QUERY = "INSERT INTO IsAboutTopic " + " VALUES(" + paper_id + ","
				+ topic_id + ")";
		return executeQuery(QUERY);
	}

	public SearchResult insertTopic(int conference_id, String name) {
		String QUERY = "INSERT INTO Topic " + "(conference_id, name) VALUES("
				+ conference_id + ",'" + name + "')";
		return executeQuery(QUERY);
	}

	public SearchResult setPersonRole(int person_id, int conference_id,
			int role_type, int state) {
		String QUERY = "INSERT INTO Role " + " VALUES(" + conference_id + ","
				+ person_id + "," + role_type + "," + state + ")";
		return executeQuery(QUERY);
	}

	public SearchResult setCoAuthorOf(int person_id, int paper_id, String name) {
		String QUERY = "INSERT INTO IsCoAuthorOf " + " VALUES(" + person_id
				+ "," + paper_id + "," + name + ")";
		return executeQuery(QUERY);
	}

}
