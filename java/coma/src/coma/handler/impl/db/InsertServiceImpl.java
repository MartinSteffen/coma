
package coma.handler.impl.db;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchResult;
import coma.handler.db.InsertService;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari</a>
 * Created on Dec 3, 2004  12:21:37 AM
 */

public class InsertServiceImpl extends Service implements InsertService{

    private static final Category log = Category
    .getInstance(InsertServiceImpl.class.getName());
    
    public InsertServiceImpl(){
        super.init();
    }
    /**
     * @see coma.handler.db.InsertService#insertPerson(coma.entities.Person)
     */
    public SearchResult insertPerson(Person person) {
        StringBuffer info = new StringBuffer();
        SearchResult result = new SearchResult();
        boolean ok = true;
        Connection conn = null;
        if(person == null){
        	info.append("ERROR: person must not be null\n");
        	ok = false;
        }
        if(ok){
        	try {
				conn = dataSource.getConnection();
			} catch (SQLException e) {
				ok = false;
				info.append("Coma could not establish a connection to the database\n");
				info.append(e.toString());
			}
        }
        if(ok){
        	String INSERT_QUERY = "INSERT INTO Person "
        	 	+ "(first_name, last_name, title, affilication,"
				+ "email, phone_number, fax_number, street,"
				+ "postal_code, city, state, country)"
				+ "VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
        	
        	try {
        		conn.setAutoCommit(false);
				PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
				int pstmtCount = 0;
				pstmt.setString(++pstmtCount,person.getFirst_name());
				pstmt.setString(++pstmtCount,person.getLast_name());
				pstmt.setString(++pstmtCount,person.getTitle());
				pstmt.setString(++pstmtCount,person.getAffiliation());
				int rows = pstmt.executeUpdate();
				if(rows != 1){
					conn.rollback();
					info.append("Person could not inserted into the database\n");
				}else{
					result.setSUCCESS(true);
					info.append("Person inserted successfully\n");
				}
			} catch (SQLException e) {
				info.append(e.toString());
			}finally{
				try {
					if(conn != null){
						conn.setAutoCommit(true);
						conn.close();
					}
				} catch (Exception e) {
					info.append("ERROR: clould not close database connection\n");;
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
         if(report == null){
         	info.append("ERROR: report must not be null\n");
         	ok = false;
         }
         if(ok){
         	try {
 				conn = dataSource.getConnection();
 			} catch (SQLException e) {
 				ok = false;
 				info.append("Coma could not establish a connection to the database\n");
 				info.append(e.toString());
 			}
         }
         if(ok){
         	String INSERT_QUERY = "INSERT INTO ReviewReport "
         	 	+ "(paper_id, reviewer_id, summary, remarks,"
 				+ "confidential) "
 				+ "VALUES(?,?,?,?,?)";
         	
         	try {
         		conn.setAutoCommit(false);
 				PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
 				
 				int rows = pstmt.executeUpdate();
 				if(rows != 1){
 					conn.rollback();
 					info.append("ReviewReport could not inserted into the database\n");
 				}else{
 					result.setSUCCESS(true);
 					info.append("ReviewReport inserted successfully\n");
 				}
 			} catch (SQLException e) {
 				info.append(e.toString());
 			}finally{
 				try {
 					if(conn != null){
 						conn.setAutoCommit(true);
 						conn.close();
 					}
 				} catch (Exception e) {
 					info.append("ERROR: clould not close database connection\n");;
 					info.append(e.toString());
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
         if(paper == null){
         	info.append("ERROR: paper must not be null\n");
         	ok = false;
         }
         if(ok){
         	try {
 				conn = dataSource.getConnection();
 			} catch (SQLException e) {
 				ok = false;
 				info.append("Coma could not establish a connection to the database\n");
 				info.append(e.toString());
 			}
         }
         if(ok){
         	String INSERT_QUERY = "INSERT INTO Paper "
         	 	+ "(conference_id, author_id, title, abstract,"
				+ "filename,state,mim_type) "
 				+ "VALUES(?,?,?,?,?,?,?)";
         	
         	try {
         		conn.setAutoCommit(false);
 				PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
 				
 				int rows = pstmt.executeUpdate();
 				if(rows != 1){
 					conn.rollback();
 					info.append("Paper could not inserted into the database\n");
 				}else{
 					result.setSUCCESS(true);
 					info.append("Paper inserted successfully\n");
 				}
 			} catch (SQLException e) {
 				info.append(e.toString());
 			}finally{
 				try {
 					if(conn != null){
 						conn.setAutoCommit(false);
 						conn.close();
 					}
 				} catch (Exception e) {
 					info.append("ERROR: clould not close database connection\n");;
 					info.append(e.toString());
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
         if(conference == null){
         	info.append("ERROR: conference must not be null\n");
         	ok = false;
         }
         if(ok){
         	try {
 				conn = dataSource.getConnection();
 			} catch (SQLException e) {
 				ok = false;
 				info.append("Coma could not establish a connection to the database\n");
 				info.append(e.toString());
 			}
         }
         if(ok){
         	String INSERT_QUERY = "INSERT INTO Conference "
         	 	+ "(name,hompage,description,abstract_submission_deadline,"
 				+ "paper_submission_deadline,review_deadline,"
 				+ "final_version_deadline,notification,conference_start,"
				+ "conference_end,min_reviews_per_paper) "
 				+ "VALUES(?,?,?,?,?,?,?,?,?,?,?)";
         	
         	try {
         		conn.setAutoCommit(false);
 				PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
 				
 				int rows = pstmt.executeUpdate();
 				if(rows != 1){
 					conn.rollback();
 					info.append("counference could not inserted into the database\n");
 				}else{
 					result.setSUCCESS(true);
 					info.append("conference inserted successfully\n");
 				}
 			} catch (SQLException e) {
 				info.append(e.toString());
 			}finally{
 				try {
 					if(conn != null){
 						conn.setAutoCommit(true);
 						conn.close();
 					}
 				} catch (Exception e) {
 					info.append("ERROR: clould not close database connection\n");;
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
	        if(rating == null){
	        	info.append("ERROR: rating must not be null\n");
	        	ok = false;
	        }
	        if(ok){
	        	try {
					conn = dataSource.getConnection();
				} catch (SQLException e) {
					ok = false;
					info.append("Coma could not establish a connection to the database\n");
					info.append(e.toString());
				}
	        }
	        if(ok){
	        	String INSERT_QUERY = "INSERT INTO Rating "
	        	 	+ "(review_id,criterion_id,grade,comment) "
					+ "VALUES(?,?,?,?)";
	        	
	        	try {
	        		conn.setAutoCommit(false);
					PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
					int rows = pstmt.executeUpdate();
					if(rows != 1){
						conn.rollback();
						info.append("rating could not inserted into the database\n");
					}else{
						result.setSUCCESS(true);
						info.append("rerson inserted successfully\n");
					}
				} catch (SQLException e) {
					info.append(e.toString());
				}finally{
					try {
						if(conn != null){
							conn.setAutoCommit(true);
							conn.close();
						}
					} catch (Exception e) {
						info.append("ERROR: clould not close database connection\n");;
						info.append(e.toString());
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
	        if(criterion == null){
	        	info.append("ERROR: criterion must not be null\n");
	        	ok = false;
	        }
	        if(ok){
	        	try {
					conn = dataSource.getConnection();
				} catch (SQLException e) {
					ok = false;
					info.append("Coma could not establish a connection to the database\n");
					info.append(e.toString());
				}
	        }
	        if(ok){
	        	String INSERT_QUERY = "INSERT INTO Criterion "
	        	 	+ "(conference_id,name,description,max_value,quality_rating) "
					+ "VALUES(?,?,?,?,?)";
	        	
	        	try {
	        		conn.setAutoCommit(false);
					PreparedStatement pstmt = conn.prepareStatement(INSERT_QUERY);
					int rows = pstmt.executeUpdate();
					//ResultSet res = pstmt.executeQuery();
					
					if(rows != 1){
						conn.rollback();
						info.append("criterion could not inserted into the database\n");
					}else{
						result.setSUCCESS(true);
						info.append("criterion inserted successfully\n");
					}
				} catch (SQLException e) {
					info.append(e.toString());
				}finally{
					try {
						if(conn != null){
							conn.setAutoCommit(true);
							conn.close();
						}
					} catch (Exception e) {
						info.append("ERROR: clould not close database connection\n");;
						info.append(e.toString());
					}
				}
	        }
	        result.setInfo(info.toString());
	        return result;
	}

}
