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
        Person p = sc.getPerson();
        boolean ok = true;
        Connection conn = null;

        if (p == null) {
            info.append("Person must not be null\n");
            ok = false;
        }
        String QUERY = "SELECT * FROM Person " + " WHERE ";

        boolean idFlag = false;
        boolean emailFlag = false;
        boolean nameFlage = false;
        boolean firstNameFlag = false;
        if (p.getId() >= 0) {
            QUERY += " id = ?";
            idFlag = true;
        } else {
            if (p.getEmail() != null) {
                QUERY += " email = ? ";
                emailFlag = true;
            } else {
                if (p.getLast_name() != null) {
                    QUERY += " first_name = ?";
                    nameFlage = true;
                }
                if (p.getFirst_name() != null) {
                    if (nameFlage) {
                        QUERY += " AND ";
                    }
                    QUERY += " last_name = ?";
                    firstNameFlag = true;
                }
            }
        }
        if (!(idFlag || emailFlag || nameFlage || firstNameFlag)) {
            info.append("No search critera was specified\n");
            ok = false;
        }
        if (ok) {
            try {

                conn = dataSource.getConnection();
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
                    ResultSet resSet = pstmt.executeQuery();
                    LinkedList ll = new LinkedList();
                    EntityCreater eCreater = new EntityCreater();

                    while (resSet.next()) {
                        Person person = eCreater.getPerson(resSet);
                        ll.add(p);
                    }
                    resSet.close();
                    resSet = null;
                    pstmt.close();
                    pstmt = null;
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
        }
        result.setInfo(info.toString());
        return result;
    }

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
        if (c.getId() >= 0) {
            QUERY += " id = ?";
            idFlag = true;
        }
        if (!idFlag) {
            info.append("No search critera was specified\n");
            ok = false;
        }
        if (ok) {
            try {

                conn = dataSource.getConnection();
                if (conn != null) {
                    PreparedStatement pstmt = conn.prepareStatement(QUERY);
                    int pstmtCounter = 0;
                    if (idFlag) {
                        pstmt.setInt(++pstmtCounter, c.getId());
                    }
                    ResultSet resSet = pstmt.executeQuery();
                    LinkedList ll = new LinkedList();
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

    public SearchResult getPaper(SearchCriteria sc) {

        StringBuffer info = new StringBuffer();
        SearchResult result = new SearchResult();
        Paper p = new Paper();
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
        if (p.getId() >= 0) {
            QUERY += " id = ?";
            idFlag = true;
        } else {
            boolean sql_and = false;
            if (p.getConference_id() >= 0) {
                if (sql_and) {
                    QUERY += " AND ";
                }
                QUERY += " conference_id = ? ";
                conferenceIdFlag = true;
                sql_and = true;
            }
            if (p.getAuthor_id() >= 0) {
                if (sql_and) {
                    QUERY += " AND ";
                }
                QUERY += " author_id = ?";
                authorIdFlage = true;
                sql_and = true;
            }
            if (p.getState() >= 0) {
                if (sql_and) {
                    QUERY += " AND ";
                }
                QUERY += " state = ?";
                stateFlag = true;
                sql_and = true;
            }

        }
        if (!(idFlag || conferenceIdFlag || authorIdFlage || stateFlag)) {
            info.append("No search critera was specified\n");
            ok = false;
        }
        if (ok) {
            try {

                conn = dataSource.getConnection();
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
                    LinkedList ll = new LinkedList();
                    EntityCreater eCreater = new EntityCreater();

                    while (resSet.next()) {
                        Paper paper = eCreater.getPaper(resSet);
                        ll.add(p);
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
        if (report.getId() >= 0) {
            QUERY += " id = ?";
            idFlag = true;
        } else {

            boolean sql_and = false;
            if (report.getPaperId() >= 0) {
                if(sql_and){
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

                conn = dataSource.getConnection();
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
                    LinkedList ll = new LinkedList();
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

}