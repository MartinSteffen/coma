package coma.handler.impl.db;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.LinkedList;

import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.ReviewReport;
import coma.entities.SearchResult;
import coma.handler.db.UpdateService;
import coma.handler.util.EntityCreater;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari </a>
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
        //if(person.getEmail() == null || person.getEmail().equals("")){
        // ok = false;
        //}
        if (ok) {
            try {

                String QUERY = "UPDATE Person SET "
                        + "VALUES(?,?,?,?,?,?,?,?,?,?,?,?)"
                        + " WHERE email = ?";
                conn = dataSource.getConnection();
                if (conn != null) {
                    PreparedStatement pstmt = conn.prepareStatement(QUERY);
                    //pstmt.setString(1, person.getName();
                    //pstmt.setString(2, sc.getLastName());
                    //...
                    //...
                    int affRows = pstmt.executeUpdate();
                    pstmt.close(); pstmt = null;
                    if(affRows != 1 && ok){
                        info.append("ERROR: Dataset could not be updated\n");
                        conn.rollback();
                    }
                    
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

}