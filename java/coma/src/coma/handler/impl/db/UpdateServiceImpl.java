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
		if (person == null) {
			ok = false;
			info
					.append("ERROR: person must not be null\n");
		}
		if(ok && (person.getEmail() == null || person.getEmail().equals(""))){
			ok = false;
			info.append("ERROR: person[email] must not be null");
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
				String UPDATE_QUERY = "INSERT INTO Person "
						+ "(first_name, last_name, title, affiliation, email,"
						+ "phone_number, fax_number, street, postal_code, city,"
						+ "state, country) "
						+ " VALUES(?,?,?,?,?,?,?,?,?,?,?,?)" + " WHERE ";
				int pstmtCounter = 0;
				PreparedStatement pstmt = conn.prepareStatement(UPDATE_QUERY);
				//TODO
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

	public SearchResult updateReviewReport(ReviewReport report) {
		//TODO
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