package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

import javax.naming.Context;
import javax.naming.InitialContext;
import javax.servlet.ServletConfig;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;
import javax.xml.transform.Source;
import javax.xml.transform.stream.StreamSource;

import org.apache.catalina.realm.RealmBase;

import coma.servlet.util.XMLHelper;

/**
 * @author <a herf="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari </a>
 */
public class Login extends HttpServlet {

	DataSource ds = null;
	StringBuffer info = new StringBuffer();
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();

	public void init(ServletConfig config) {
		try {
			Context initCtx = new InitialContext();
			Context envCtx = (Context) initCtx.lookup("java:comp/env");
			ds = (DataSource) envCtx.lookup("jdbc/coma");
			super.init(config);
		} catch (Exception e) {
			helper.addInfo(e.getMessage().toString(), info);
		}
	}
	public void doGet(
		HttpServletRequest request,
		HttpServletResponse response) {
		HttpSession session;
		session = request.getSession(true);
		String user = request.getParameter("userID");
		String passwd = request.getParameter("passwd");
		try {
			
			if ((user == null || user.equals(""))
				|| (passwd == null || passwd.equals(""))) {

				session.setAttribute("login", null);
			}
			if (validatePasswd(user, passwd)) {
				session.setAttribute("login", new String("ok"));
				response.sendRedirect(
					"/coma/Chair?show=html&loechen=");
			} else {
				session.setAttribute("login", null);
				helper.addInfo("INFO: Login gescheitert, versuchen Sie es bitte noch einmal\n", info);
			}
			//Logout
			if(request.getParameter("logout") != null){
				session.setAttribute("login", null);
			}
			
			String path = getServletContext().getRealPath("");
			String xslt = path + "/" + "style/xsl/login.xsl";
			PrintWriter out = response.getWriter();
			
			result.append("<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>\n");
			result.append("<result>\n");
			result.append("<info>\n");
			result.append(info.toString());
			result.append("</info>\n");
			result.append("</result>\n");
			
			response.setContentType("text/html; charset=ISO-8859-1");
			Source xmlSource =
				new StreamSource(new StringReader(result.toString()));
			Source xsltSource = new StreamSource(xslt);
			XMLHelper.process(xmlSource, xsltSource, out);
			out.flush();

		} catch (IOException e) {
			e.printStackTrace();
		} finally {
			info = new StringBuffer();
			result = new StringBuffer();
		}
	}

	public void doPost(
		HttpServletRequest request,
		HttpServletResponse response) {
		doGet(request, response);
	}

	private boolean validatePasswd(String user, String passwd) {
		boolean isValid = false;
		try {
			Connection conn = ds.getConnection();
			String QUERY =
				"SELECT Passwort FROM users WHERE login = '" + user + "'";
			Statement stmt = conn.createStatement();
			ResultSet resSet = stmt.executeQuery(QUERY);
			if (resSet.next()) {
				String code = resSet.getString("Passwort");
				if (RealmBase.Digest(passwd, "SHA").equals(code)) {
					isValid = true;
				}
			}
			resSet.close();
			resSet = null;
			stmt.close();
			stmt = null;
			if (conn != null) {
				conn.close();
			}
		} catch (SQLException e) {
			helper.addInfo(e.getMessage(), info);
			e.printStackTrace();
		}
		return isValid;
	}

}
