
package coma.handler.impl.db;

import javax.naming.Context;
import javax.naming.InitialContext;
import javax.sql.DataSource;

import org.apache.log4j.Category;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari</a>
 * Created on Dec 2, 2004  11:09:51 PM
 */

public class Service {
    
    private static final Category log =
		Category.getInstance(Service.class.getName());
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
}
