package coma.handler.util;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Properties;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de>Mohamed Albari </a>
 */
public class DBConnectionCreater {

    public static Connection getConnection() {
        Properties props = new Properties();
        Connection result = null;
        try {
            //props.load(
            //	new
            // FileInputStream("/home/wprguest3/webapps/coma/conf/db.config"));
            String driver = "org.gjt.mm.mysql.Driver";
            String url =
                   "jdbc:mysql://snert.informatik.uni-kiel.de/coma3?user=wprguest3";
            //String url = "jdbc:mysql://localhost/coma3?user=root";

            Class.forName(driver);
            result = DriverManager.getConnection(url);
        } catch (SQLException e) {
            System.out.println(e.getClass() + e.getMessage().toString());
        } catch (ClassNotFoundException e1) {
            System.out.println(e1.getClass() + e1.getMessage().toString());
        }

        return result;
    }

    public static void main(String[] args) {
        Connection con = getConnection();
        System.out.println(con.toString());
    }
}