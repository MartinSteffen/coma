package coma.handler.impl.db;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.LinkedList;
import java.util.List;

import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.handler.db.ReadService;
import coma.handler.util.EntityCreater;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari </a>
 *         Created on Dec 2, 2004 11:09:10 PM
 */

public class ReadServiceImpl extends Service implements ReadService {

	private static final Category

	log = Category.getInstance(ReadServiceImpl.class.getName());

	public ReadServiceImpl() {
		// super.init();
	}

	/**
	 * searchs for person(s) specified in <code>SearchCriretia</code> sc. The
	 * Search function is defined as following: <lo>
	 * <li>1. if sc.Person.id >= 0, then search for the person corresponding to
	 * this id.</li>
	 * <li>If 1 not ablicable, then: <br>
	 * 2. if sc.Person.email != null, then search for the person corresponding
	 * to this email address.</li>
	 * <li>If 2 not ablicable: <br>
	 * 3. if sc.Person.last_name != null, then search for persons with this
	 * lastname 4. if sc.Person.first_name != null, then searchfor persons with
	 * this firstname</li>
	 * </lo>
	 * 
	 * @param <code>SearchCriteria</code> sc, crtiteria to use for searching.
	 * @return <code>SearchResult</code> result
	 */
	public SearchResult getPerson(SearchCriteria sc) {

		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Person p = sc.getPerson();
		boolean ok = true;
		Connection conn = null;

		if (p == null) {
			info.append("Person must not be null\n");
			ok = false;
		}
		String QUERY = "SELECT Person.* FROM Person WHERE ";

		boolean idFlag = false;
		boolean emailFlag = false;
		boolean nameFlage = false;
		boolean firstNameFlag = false;
		boolean stateFlag = false;
		boolean roleFlag = false;
		if (p.getId() > 0) {
			QUERY += " id = ?";
			idFlag = true;
		} else {
			if (p.getEmail() != null) {
				QUERY += " email = ? ";
				emailFlag = true;
			} else {
				boolean and = false;
				if (p.getLast_name() != null) {
					if (and) {
						QUERY += " AND ";
					}
					QUERY += " first_name = ?";
					nameFlage = true;
					and = true;
				}
				if (p.getFirst_name() != null) {
					if (and) {
						QUERY += " AND ";
					}
					QUERY += " last_name = ?";
					firstNameFlag = true;
					and = true;
				}
				if (p.getState() != null) {
					if (and) {
						QUERY += " AND ";
					}
					QUERY += " Person.state = ?";
					stateFlag = true;
					and = true;
				}
				if (p.getRole_type() > 0) {
					if (and) {
						QUERY += " AND ";
					}
					QUERY += " Role.role_type = ? AND Role.person_id = Person.id";
					roleFlag = true;
					and = true;
				}
			}
		}
		if (!(idFlag || roleFlag || emailFlag || stateFlag || nameFlage || firstNameFlag)) {
			info.append("No search critera was specified\n");
			ok = false;
		}
		if (ok) {
			try {
				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					PreparedStatement pstmt = conn.prepareStatement(QUERY);
					int pstmtCounter = 0;
					if (idFlag) {
						pstmt.setInt(++pstmtCounter, p.getId());
					}
					if (emailFlag) {
						pstmt.setString(++pstmtCounter, p.getEmail());
					}
					if (nameFlage) {
						pstmt.setString(++pstmtCounter, p.getLast_name());
					}
					if (firstNameFlag) {
						pstmt.setString(++pstmtCounter, p.getFirst_name());
					}
					if (stateFlag) {
						pstmt.setString(++pstmtCounter, p.getState());
					}
					if (roleFlag) {
						pstmt.setInt(++pstmtCounter, p.getRole_type());
					}
					ResultSet resSet = pstmt.executeQuery();
					LinkedList<Person> ll = new LinkedList<Person>();
					EntityCreater eCreater = new EntityCreater();
					while (resSet.next()) {
						Person person = eCreater.getPerson(resSet);
						ll.add(person);
					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;
					Person[] persons = new Person[ll.size()];
					for (int i = 0; i < ll.size(); i++) {
						persons[i] = (Person) ll.get(i);
					}
					result.setResultObj(persons);
				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
				}
			} catch (SQLException e) {
				info.append("ERROR: " + e.toString() + "\n");
				System.out.println(e);
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

	/**
	 * Searchs for a conference specified by a given id, sc.Conference.id
	 * 
	 * @param <code>SearchCriteria</code> sc, Criteria to use for searching
	 * @return <code>SearchResult</code> result
	 */
	public SearchResult getConference(SearchCriteria sc) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Conference c = sc.getConference();
		boolean ok = true;
		Connection conn = null;

		if (c == null) {
			info.append("Confernce must not be null\n");
			ok = false;
		}
		String QUERY = "SELECT * FROM Conference " + " WHERE ";

		boolean idFlag = false;
		if (c.getId() > 0) {
			QUERY += " id = ?";
			idFlag = true;
		}
		if (!idFlag) {
			info.append("No search critera was specified\n");
			ok = false;
		}
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					PreparedStatement pstmt = conn.prepareStatement(QUERY);
					int pstmtCounter = 0;
					if (idFlag) {
						pstmt.setInt(++pstmtCounter, c.getId());
					}
					ResultSet resSet = pstmt.executeQuery();
					LinkedList<Conference> ll = new LinkedList<Conference>();
					EntityCreater eCreater = new EntityCreater();

					while (resSet.next()) {
						Conference conf = eCreater.getConference(resSet);
						ll.add(conf);
					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;
					Conference[] conference = new Conference[ll.size()];
					for (int i = 0; i < conference.length; i++) {
						conference[i] = (Conference) ll.get(i);
					}
					result.setResultObj(conference);
				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
				}
			} catch (SQLException e) {
				info.append("ERROR: " + e.toString() + "\n");
				System.out.println(e.toString());
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

	/**
	 * Searchs for Paper(s) spcified by <code>SearchCriteria</code> sc. The
	 * search function is defined as following: <lo>
	 * <li></li>
	 * <li></li>
	 * </lo>
	 * 
	 * @param <code>SearchCriteria</code> sc, Criteria to use for searching.
	 * @return <code>SearchResult</code> result
	 */
	public SearchResult getPaper(SearchCriteria sc) {

		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Paper p = sc.getPaper();
		boolean ok = true;
		Connection conn = null;

		if (p == null) {
			info.append("Paper must not be null\n");
			ok = false;
		}
		String QUERY = "SELECT * FROM Paper " + " WHERE ";
		boolean idFlag = false;
		boolean conferenceIdFlag = false;
		boolean authorIdFlage = false;
		boolean stateFlag = false;
		boolean allFlag = false;
		// with id=-2 get all paper
		if (p.getId()==-2)
		{
			QUERY = "SELECT * FROM Paper";
			allFlag=true;
		}
		else
		{
			if (p.getId() >= 0) 
			{
				QUERY += " id = ?";
				idFlag = true;
			} 
			else {
				boolean sql_and = false;
				if (p.getConference_id() > 0) {
					if (sql_and) {
						QUERY += " AND ";
					}
					QUERY += " conference_id = ? ";
					conferenceIdFlag = true;
					sql_and = true;
				}
				if (p.getAuthor_id() > 0) {
					if (sql_and) {
						QUERY += " AND ";
					}
					QUERY += " author_id = ?";
					authorIdFlage = true;
					sql_and = true;
				}
				if (p.getState() > 0) {
					if (sql_and) {
						QUERY += " AND ";
					}
					QUERY += " state = ?";
					stateFlag = true;
					sql_and = true;
				}
			}
		}
		if (!(idFlag || allFlag || conferenceIdFlag || authorIdFlage || stateFlag)) {
			info.append("No search critera was specified\n");
			ok = false;
		}
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					PreparedStatement pstmt = conn.prepareStatement(QUERY);
					int pstmtCounter = 0;
					if (idFlag) {
						pstmt.setInt(++pstmtCounter, p.getId());
					}
					if (conferenceIdFlag) {
						pstmt.setInt(++pstmtCounter, p.getConference_id());
					}
					if (authorIdFlage) {
						pstmt.setInt(++pstmtCounter, p.getAuthor_id());
					}
					if (stateFlag) {
						pstmt.setInt(++pstmtCounter, p.getState());
					}
					ResultSet resSet = pstmt.executeQuery();
					LinkedList<Paper> ll = new LinkedList<Paper>();
					EntityCreater eCreater = new EntityCreater();

					while (resSet.next()) {
						Paper paper = eCreater.getPaper(resSet);;
						ll.add(paper);
					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;
					Paper[] papers = new Paper[ll.size()];
					for (int i = 0; i < papers.length; i++) {
						papers[i] = (Paper) ll.get(i);
					}
					result.setResultObj(papers);
				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
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

	public SearchResult getReviewReport(SearchCriteria sc) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		ReviewReport report = sc.getReviewReport();
		boolean ok = true;
		Connection conn = null;

		if (report == null) {
			info.append("ReviewReport must not be null\n");
			ok = false;
		}
		String QUERY = "SELECT * FROM ReviewReport " + " WHERE ";

		boolean idFlag = false;
		boolean PaperIdFlag = false;
		boolean reviewerIdFlag = false;
		if (report.getId() > 0) {
			QUERY += " id = ?";
			idFlag = true;
		} else {

			boolean sql_and = false;
			if (report.getPaperId() >= 0) {
				if (sql_and) {
					QUERY += " AND ";
				}
				QUERY += " paper_id = ?";
				PaperIdFlag = true;
				sql_and = true;
			}
			if (report.getReviewerId() >= 0) {
				if (sql_and) {
					QUERY += " AND ";
				}
				QUERY += " reviewer_id = ?";
				reviewerIdFlag = true;
			}

		}
		if (!(idFlag || PaperIdFlag || reviewerIdFlag)) {
			info.append("No search critera was specified\n");
			ok = false;
		}
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					PreparedStatement pstmt = conn.prepareStatement(QUERY);
					int pstmtCounter = 0;
					if (idFlag) {
						pstmt.setInt(++pstmtCounter, report.getId());
					}
					if (PaperIdFlag) {
						pstmt.setInt(++pstmtCounter, report.getPaperId());
					}
					if (reviewerIdFlag) {
						pstmt.setInt(++pstmtCounter, report.getReviewerId());
					}
					ResultSet resSet = pstmt.executeQuery();
					LinkedList<ReviewReport> ll = new LinkedList<ReviewReport>();
					EntityCreater eCreater = new EntityCreater();

					while (resSet.next()) {
						ReviewReport rep = eCreater.getReviewReport(resSet);
						ll.add(rep);
					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;
					ReviewReport[] reports = new ReviewReport[ll.size()];
					for (int i = 0; i < reports.length; i++) {
						reports[i] = (ReviewReport) ll.get(i);
					}
					result.setResultObj(reports);
				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
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

	public SearchResult getRating(SearchCriteria sc) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Rating rating = sc.getRating();
		boolean ok = true;
		Connection conn = null;

		if (rating == null) {
			info.append("rating must not be null\n");
			ok = false;
		}
		String QUERY = "SELECT * FROM Rating " + " WHERE ";

		boolean reportIdFlag = false;
		boolean criterionIdFlag = false;
		if (rating.getReviewReportId() > 0) {
			QUERY += " review_id = ? ";
			reportIdFlag = true;
		}
		if (rating.getCriterionId() > 0) {
			if (reportIdFlag) {
				QUERY += " AND ";
			}
			QUERY += " criterion_id = ? ";
			criterionIdFlag = true;
		}
		if (!(reportIdFlag || criterionIdFlag)) {
			info.append("No search critera was specified\n");
			ok = false;
		}
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					PreparedStatement pstmt = conn.prepareStatement(QUERY);
					int pstmtCounter = 0;
					if (reportIdFlag) {
						pstmt
								.setInt(++pstmtCounter, rating
										.getReviewReportId());
					}
					if (criterionIdFlag) {
						pstmt.setInt(++pstmtCounter, rating.getCriterionId());
					}
					ResultSet resSet = pstmt.executeQuery();
					LinkedList<Rating> ll = new LinkedList<Rating>();
					EntityCreater eCreater = new EntityCreater();

					while (resSet.next()) {
						Rating rat = eCreater.getRating(resSet);
						ll.add(rat);
					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;
					Rating[] ratings = new Rating[ll.size()];
					for (int i = 0; i < ratings.length; i++) {
						ratings[i] = (Rating) ll.get(i);
					}
					result.setResultObj(ratings);
				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
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

	public SearchResult getCriterion(SearchCriteria sc) {

		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Criterion criterion = sc.getCriterion();
		boolean ok = true;
		Connection conn = null;

		if (criterion == null) {
			info.append("Crtiterion must not be null\n");
			ok = false;
		}
		String QUERY = "SELECT * FROM Criterion " + " WHERE ";

		boolean idFlag = false;
		boolean conferenceIdFlag = false;
		if (criterion.getId() > 0) {
			QUERY += " id = ?";
			idFlag = true;
		}
		if (criterion.getConferenceId() > 0) {
			if (idFlag) {
				QUERY += " AND ";
			}
			QUERY += " conference_id = ?";
			conferenceIdFlag = true;
		}
		if (!(idFlag || conferenceIdFlag)) {
			info.append("No search critera was specified\n");
			ok = false;
		}
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					PreparedStatement pstmt = conn.prepareStatement(QUERY);
					int pstmtCounter = 0;
					if (idFlag) {
						pstmt.setInt(++pstmtCounter, criterion.getId());
					}
					if (conferenceIdFlag) {
						pstmt.setInt(++pstmtCounter, criterion
								.getConferenceId());
					}
					ResultSet resSet = pstmt.executeQuery();
					List<Criterion> ll = new LinkedList<Criterion>();
					EntityCreater eCreater = new EntityCreater();

					while (resSet.next()) {
						Criterion crit = eCreater.getCriterion(resSet);
						ll.add(crit);
					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;
					Criterion[] crits = new Criterion[ll.size()];
					for (int i = 0; i < crits.length; i++) {
						crits[i] = (Criterion) ll.get(i);
					}
					result.setResultObj(crits);
				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
				}
			} catch (SQLException e) {
				System.out.println(e.toString());
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

	public ResultSet executeQuery(String sqlQuery) {
		ResultSet result = null;

		try {
			Connection conn = getConnection();

			if (conn != null) {
				Statement stmt = conn.createStatement();
				result = stmt.executeQuery(sqlQuery);
			}
		} catch (Exception e) {
			System.out.println(e);
		}
		return result;
	}

	/**
	 * @see coma.handler.db.ReadService#getPersonRoles(int, int)
	 */
	public SearchResult getPersonRoles(int conference_id, int person_id) {

		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;

		if (conference_id < 0) {
			info.append("Conference_id must not be less than 0\n");
			ok = false;
		}
		if (person_id < 0) {
			info.append("Person_id must not be less than 0\n");
			ok = false;
		}
		String QUERY = "SELECT role_type FROM Role " + " WHERE "
				+ " conference_id = " + conference_id + " AND person_id = "
				+ person_id;
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					Statement pstmt = conn.createStatement();
					ResultSet resSet = pstmt.executeQuery(QUERY);
					List<Integer> ll = new LinkedList<Integer>();

					while (resSet.next()) {
						ll.add(resSet.getInt("role_type"));
					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;
					int[] roles = new int[ll.size()];
					for (int i = 0; i < roles.length; i++) {
						roles[i] = (Integer) ll.get(i);
					}
					result.setResultObj(roles);
				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
				}
			} catch (SQLException e) {
				System.out.println(e.toString());
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

	/**
	 * @see coma.handler.db.ReadService#isCoAuthorOf(int, int)
	 */
	public SearchResult isCoAuthorOf(int paper_id, int person_id) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;

		if (paper_id < 0) {
			info.append("paper_id must not be less than 0\n");
			ok = false;
		}
		if (person_id < 0) {
			info.append("person_id must not be less than 0\n");
			ok = false;
		}
		String QUERY = "SELECT COUNT(*) FROM IsCoAuthorOf " + " WHERE "
				+ " paper_id = " + paper_id + " AND person_id = " + person_id;
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					Statement pstmt = conn.createStatement();
					ResultSet resSet = pstmt.executeQuery(QUERY);

					while (resSet.next()) {
						if (resSet.getInt(1) > 0) {
							result.setResultObj(new Boolean(true));
						} else {
							result.setResultObj(new Boolean(false));
						}

					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;

				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
				}
			} catch (SQLException e) {
				System.out.println(e.toString());
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

	/**
	 * @see coma.handler.db.ReadService#isAboutTopic(int, int)
	 */
	public SearchResult isAboutTopic(int paper_id, int topic_id) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;

		if (paper_id < 0) {
			info.append("paper_id must not be less than 0\n");
			ok = false;
		}
		if (topic_id < 0) {
			info.append("topic_id must not be less than 0\n");
			ok = false;
		}
		String QUERY = "SELECT COUNT(*) FROM IsAboutTopic " + " WHERE "
				+ " paper_id = " + paper_id + " AND topic_id = " + topic_id;
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					Statement pstmt = conn.createStatement();
					ResultSet resSet = pstmt.executeQuery(QUERY);

					while (resSet.next()) {
						if (resSet.getInt(1) > 0) {
							result.setResultObj(new Boolean(true));
						} else {
							result.setResultObj(new Boolean(false));
						}

					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;

				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
				}
			} catch (SQLException e) {
				System.out.println(e.toString());
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

	/**
	 * @see coma.handler.db.ReadService#getPreferedPapers(int)
	 */
	public SearchResult getPreferedPapers(int person_id) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;

		if (person_id < 0) {
			info.append("Person_id must not be less than 0\n");
			ok = false;
		}
		String QUERY = "SELECT paper_id FROM PrefersPaper " + " WHERE "
				+ " person_id = " + person_id;
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					Statement pstmt = conn.createStatement();
					ResultSet resSet = pstmt.executeQuery(QUERY);
					List<Integer> ll = new LinkedList<Integer>();

					while (resSet.next()) {
						ll.add(resSet.getInt("paper_id"));
					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;
					int[] paper_ids = new int[ll.size()];
					for (int i = 0; i < paper_ids.length; i++) {
						paper_ids[i] = (Integer) ll.get(i);
					}
					result.setResultObj(paper_ids);
				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
				}
			} catch (SQLException e) {
				System.out.println(e.toString());
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

	/**
	 * @see coma.handler.db.ReadService#getDeniedPapers(int)
	 */
	public SearchResult getDeniedPapers(int person_id) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;

		if (person_id < 0) {
			info.append("Person_id must not be less than 0\n");
			ok = false;
		}
		String QUERY = "SELECT paper_id FROM DeniesPaper " + " WHERE "
				+ " person_id = " + person_id;
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					Statement pstmt = conn.createStatement();
					ResultSet resSet = pstmt.executeQuery(QUERY);
					List<Integer> ll = new LinkedList<Integer>();

					while (resSet.next()) {
						ll.add(resSet.getInt("paper_id"));
					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;
					int[] paper_ids = new int[ll.size()];
					for (int i = 0; i < paper_ids.length; i++) {
						paper_ids[i] = (Integer) ll.get(i);
					}
					result.setResultObj(paper_ids);
				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
				}
			} catch (SQLException e) {
				System.out.println(e.toString());
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

	/**
	 * @see coma.handler.db.ReadService#getExecludedPapers(int)
	 */
	public SearchResult getExecludedPapers(int person_id) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;

		if (person_id < 0) {
			info.append("Person_id must not be less than 0\n");
			ok = false;
		}
		String QUERY = "SELECT paper_id FROM ExcludesPaper " + " WHERE "
				+ " person_id = " + person_id;
		if (ok) {
			try {

				// conn = dataSource.getConnection();
				conn = getConnection();
				if (conn != null) {
					Statement pstmt = conn.createStatement();
					ResultSet resSet = pstmt.executeQuery(QUERY);
					List<Integer> ll = new LinkedList<Integer>();

					while (resSet.next()) {
						ll.add(resSet.getInt("paper_id"));
					}
					resSet.close();
					resSet = null;
					pstmt.close();
					pstmt = null;
					int[] paper_ids = new int[ll.size()];
					for (int i = 0; i < paper_ids.length; i++) {
						paper_ids[i] = (Integer) ll.get(i);
					}
					result.setResultObj(paper_ids);
				} else {
					info.append("ERROR: coma could not establish a "
							+ "connection to the database\n");
				}
			} catch (SQLException e) {
				System.out.println(e.toString());
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

	/**
	 * @see coma.handler.db.ReadService#getMessages(int, int, int)
	 */
	public SearchResult getMessages(int msg_id, int forum_id, int sender_id) {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see coma.handler.db.ReadService#getForum(int, int)
	 */
	public SearchResult getForum(int forum_id, int conference_id) {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see coma.handler.db.ReadService#getPreferedTpoic(int)
	 */
	public SearchResult getPreferedTpoic(int person_id) {
		// TODO Auto-generated method stub
		return null;
	}

}
