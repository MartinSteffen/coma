package coma.handler.impl.db;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.LinkedList;

import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchResult;
import coma.handler.db.UpdateService;
import coma.handler.util.PreparedStatementSetter;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari </a>
 *         Created on Dec 2, 2004 11:25:45 PM
 */

public class UpdateServiceImpl extends Service implements UpdateService {

	private static final Category log = Category
			.getInstance(UpdateServiceImpl.class.getName());

	public UpdateServiceImpl() {
		super.init();
	}

	public SearchResult updatePerson(Person person) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		Connection conn = null;
		boolean ok = true;
		if (person.getEmail() == null && person.getId() < 0) {
			ok = false;
			info
					.append("ERROR: No person was spcified, Person.email or person.id must not be null\n");
		}
		if(ok){
			try {
				conn = dataSource.getConnection();
			} catch (SQLException e) {
				ok = false;
				info.append("ERROR: Coma could not establish a connection to the database");
				info.append(e.toString() + "\n");
			}
		}
		if (ok) {
			try {
				String TEST_QUERY = "SELECT COUNT(*) FROM Person WHERE ";
				String INSERT_QUERY = "INSERT INTO Person "
						+ "(first_name, last_name, title, affiliation, email,"
						+ "phone_number, fax_number, street, postal_code, city,"
						+ "state, country, password) "
						+ " VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)" + " WHERE ";
				String UPDATE_QUERY = "UPDATE Person SET ......";
				
				if (person.getId() >= 0) {
					INSERT_QUERY += " id = ?";
					TEST_QUERY += "id = " + person.getId();
				} else {
					INSERT_QUERY += " email = ?";
					TEST_QUERY += "email = '"+person.getEmail()+"'";
				}
				Statement stmt = conn.createStatement();
				PreparedStatement pstmt = null;
				ResultSet testRsSet = stmt.executeQuery(TEST_QUERY);
				int count = 0;
				if(testRsSet.next()){
					count = testRsSet.getInt(1);
				}
				if(count >= 1){
					pstmt = conn.prepareStatement(INSERT_QUERY);
				}else{
					pstmt = conn.prepareStatement(UPDATE_QUERY);
				}
				
				int pstmtCounter = 0;
				PreparedStatementSetter.prepareStatement(pstmt, person, pstmtCounter);
				int affRows = pstmt.executeUpdate();
				pstmt.close();
				pstmt = null;
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
		return null;
	}

	public SearchResult updateReviewReport(ReviewReport report) {
		// TODO Auto-generated method stub
		return null;
	}

	public SearchResult updatePaper(Paper paper) {
		// TODO Auto-generated method stub
		return null;
	}

	public SearchResult updateConference(Conference conference) {
		// TODO Auto-generated method stub
		return null;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see coma.handler.db.UpdateService#updateRating(coma.entities.Rating)
	 */
	public SearchResult updateRating(Rating rating) {
		// TODO Auto-generated method stub
		return null;
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