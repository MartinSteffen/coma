package coma.handler.impl.db;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Properties;

import javax.naming.Context;
import javax.naming.InitialContext;
import javax.sql.DataSource;

import coma.entities.SearchResult;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari </a>
 *         Created on Dec 2, 2004 11:09:51 PM
 */

public class Service {

	static DataSource dataSource = null;

	static boolean configured = false;

	public static void init() {
		try {
			Context initCtx = new InitialContext();
			Context envCtx = (Context) initCtx.lookup("java:comp/env");
			dataSource = (DataSource) envCtx.lookup("jdbc/coma3");
			if (dataSource != null) {
				configured = true;
			}

		} catch (Exception e) {
		    //System.out.println("coma init: " + e.toString());
		}
	}

	public static Connection getConnection() {
		Properties prop = new Properties();
		Connection result = null;

		try {
			if (!configured) {
				init();
			}
			if (configured) {
			    System.out.println("Connection pool initialized ok");
			    result = dataSource.getConnection();
			} else {
				DriverManager.setLoginTimeout(10);
				try {
					prop.load(new FileInputStream("db.pproperties"));
					String driver = prop.getProperty("db.driver");
					String url = prop.getProperty("db.url");
					String user = prop.getProperty("db.user");
					String pass = prop.getProperty("db.password");
					result = DriverManager.getConnection(url, user, pass);
				} catch (FileNotFoundException e2) {
					System.out.println(e2);
					String driver = "org.gjt.mm.mysql.Driver";
					String url = "jdbc:mysql://vs170142.vserver.de/coma3";
					Class.forName(driver);
					result = DriverManager.getConnection(url, "coma3",
							"TervArHorhy");
				} catch (IOException e2) {
					System.out.println(e2);
				}
			}
		} catch (SQLException e) {
			System.out.println(e.getClass() + e.getMessage().toString());
		} catch (ClassNotFoundException e1) {
			System.out.println(e1.getClass() + e1.getMessage().toString());
		}

		return result;
	}

	protected SearchResult executeQuery(String query) {
		StringBuffer info = new StringBuffer();
		SearchResult result = new SearchResult();
		boolean ok = true;
		Connection conn = null;

		try {
			conn = getConnection();
		} catch (Exception e) {
			ok = false;
			info
					.append("Coma could not establish a connection to the database\n");
			info.append(e.toString());
			System.out.println(e);
		}
		if (ok) {

			try {
				conn.setAutoCommit(false);
				Statement pstmt = conn.createStatement();
				int rows = pstmt.executeUpdate(query);
				if (rows < 1) {
					conn.rollback();
					info.append("No data have been changed \n");
				} else {
					result.setSUCCESS(true);
					info
							.append("Transaction has been performed successfully\n");
				}
			} catch (SQLException e) {
				System.out.println(e);
			} finally {
				try {
					if (conn != null) {
						conn.setAutoCommit(true);
						conn.close();
					}
				} catch (Exception e) {
					info
							.append("ERROR: clould not close database connection\n");

					System.out.println(e);
				}
			}
		}
		result.setInfo(info.toString());
		return result;
	}
}
