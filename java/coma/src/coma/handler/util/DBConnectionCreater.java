package coma.handler.util;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Properties;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de>Mohamed Albari</a>
 */
public class DBConnectionCreater {
	
	public Connection getConnection() {
		Properties props = new Properties();
		Connection result = null;
		try {
			props.load(
				new FileInputStream("/home/tomcat/webapps/coma/conf/db.config"));
			String driver = props.getProperty("driver").trim();
			String url = props.getProperty("url").trim();

			Class.forName(driver);
			result = DriverManager.getConnection(url);
		} catch (SQLException e) {
			System.out.println(e.getClass() + e.getMessage().toString());
		} catch (ClassNotFoundException e1) {
			System.out.println(e1.getClass() + e1.getMessage().toString());
		} catch (FileNotFoundException e) {
			System.out.println(e.getClass() + e.getMessage().toString());
		} catch (IOException e) {
			System.out.println(e.getClass() + e.getMessage().toString());
		}

		return result;
	}
}
