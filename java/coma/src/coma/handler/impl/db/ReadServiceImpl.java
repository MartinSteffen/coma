package coma.handler.impl.db;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.LinkedList;

import org.apache.log4j.Category;

import coma.entities.Person;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.handler.db.ReadService;
import coma.handler.util.EntityCreater;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari </a>
 *         Created on Dec 2, 2004 11:09:10 PM
 */

public class ReadServiceImpl extends Service implements ReadService {

    private static final Category log = Category
            .getInstance(ReadServiceImpl.class.getName());

    public ReadServiceImpl() {
        super.init();
    }

    public SearchResult getPerson(SearchCriteria sc) {

        StringBuffer info = new StringBuffer();
        SearchResult result = new SearchResult();
        Connection conn = null;

        try {

            String QUERY = 
                "SELECT * FROM Person "
                + " WHERE email = ?"
                + "   AND conference = ?";
            conn = dataSource.getConnection();
            if (conn != null) {
                PreparedStatement pstmt = conn.prepareStatement(QUERY);
                //pstmt.setString(1, sc.getEmail());
                //pstmt.setString(2, sc.getConference());
                ResultSet resSet = pstmt.executeQuery();
                LinkedList ll = new LinkedList();
                EntityCreater eCreater = new EntityCreater();

                while (resSet.next()) {
                    Person p = eCreater.getPerson(resSet);
                    ll.add(p);
                }
                resSet.close(); resSet = null;
                pstmt.close(); pstmt = null;
                Person[] persons = new Person[ll.size()];
                for (int i = 0; i < persons.length; i++) {
                    persons[i] = (Person) ll.get(i);
                }
                result.setResultObj(persons);
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
        result.setInfo(info.toString());
        return result;
    }

    public SearchResult getConference(SearchCriteria sc) {
        // TODO Auto-generated method stub
        return null;
    }
    public SearchResult getPaper(SearchCriteria sc) {
        // TODO Auto-generated method stub
        return null;
    }

    /*
     * (non-Javadoc)
     * 
     * @see coma.handler.db.ReadService#getReviewReport(coma.entities.SearchCriteria)
     */
    public SearchResult getReviewReport(SearchCriteria sc) {
        // TODO Auto-generated method stub
        return null;
    }

}