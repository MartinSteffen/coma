package coma.handler.impl.db;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Properties;

import javax.naming.Context;
import javax.naming.InitialContext;
import javax.sql.DataSource;


/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari</a>
 * Created on Dec 2, 2004  11:09:51 PM
 */

public class Service {
    
	DataSource dataSource = null;
	boolean configured = false;
    
	
	public void init() {
		try {
			Context initCtx = new InitialContext();
			Context envCtx = (Context) initCtx.lookup("java:comp/env");
			dataSource = (DataSource) envCtx.lookup("jdbc/coma");
			if (dataSource != null) {
				configured = true;
			}

		} catch (Exception e) {
			System.out.println("coma init: " + e.toString());
		}
	}
	
	 public static Connection getConnection() {
        Properties props = new Properties();
        Connection result = null;
        try {
            String driver = "org.gjt.mm.mysql.Driver";
            String url = "jdbc:mysql://vs170142.vserver.de/coma3";
            Class.forName(driver);
            result = DriverManager.getConnection(url,"coma3","TervArHorhy");
        } catch (SQLException e) {
            System.out.println(e.getClass() + e.getMessage().toString());
        } catch (ClassNotFoundException e1) {
            System.out.println(e1.getClass() + e1.getMessage().toString());
        }

        return result;
    }

	public static void main(String[] args){

		Connection con = getConnection();
		System.out.println(con.toString());
	}
}
