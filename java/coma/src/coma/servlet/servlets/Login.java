package coma.servlet.servlets;


import java.io.PrintWriter;
import java.io.StringReader;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.xml.transform.stream.StreamSource;

import coma.entities.Person;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.handler.impl.db.ReadServiceImpl;
import coma.servlet.util.FormParameters;
import coma.servlet.util.SessionAttribs;
import coma.servlet.util.XMLHelper;

/**
 * @author Peter Kauffels & Mohamed Z. Albari
 */
public class Login extends HttpServlet {

	Person myPerson = null;

	public void doGet(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, java.io.IOException {

		HttpSession session;
		session = request.getSession(true);
		String email = request.getParameter(FormParameters.EMAIL);
		String passwd = request.getParameter(FormParameters.PASSWORD);

		StringBuffer result = new StringBuffer();
		XMLHelper helper = new XMLHelper();

		String path = getServletContext().getRealPath("");
		String xslt = path + "/style/xsl/index.xsl";
		PrintWriter out = response.getWriter();

		helper.addXMLHead(result);
		result.append("<login>\n");

		if ((email == null || email.equals(""))
				|| (passwd == null || passwd.equals(""))) {
			session.setAttribute(SessionAttribs.PERSON, null);
			result.append(XMLHelper.tagged("form_error"));
		}

		else {
			if (validatePasswd(email, passwd)) {

				session.setAttribute(SessionAttribs.PERSON, myPerson);
				result.append(XMLHelper.tagged("success"));
			}

			else {
				session.setAttribute(SessionAttribs.PERSON, null);
				result.append(XMLHelper.tagged("password_error",email+passwd));
			}

		}
		//Logout
		// XXX maybe adapt to coma.servlet.util.SessionAttribs. Ulrich
		//if(request.getParameter("logout") != null){
		// XXX maybe adapt to coma.servlet.util.SessionAttribs. Ulrich
		//	session.setAttribute("login", null);
		//}

		result.append("</login>\n");
		response.setContentType("text/html; charset=ISO-8859-15");
		StreamSource xmlSource = new StreamSource(new StringReader(result
				.toString()));
		StreamSource xsltSource = new StreamSource(xslt);
		XMLHelper.process(xmlSource, xsltSource, out);
		out.flush();

	}

	public void doPost(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, java.io.IOException {

		doGet(request, response);
	}

	private boolean validatePasswd(String email, String passwd) {

		boolean isValid = false;

		ReadServiceImpl myReadService = new ReadServiceImpl();
		Person mySearchPerson = new Person(-1);
		mySearchPerson.setEmail(email);
		SearchCriteria mysc = new SearchCriteria();
		mysc.setPerson(mySearchPerson);
		SearchResult mySR = myReadService.getPerson(mysc);
		if (mySR != null){
			Person[] personArray = (Person[]) mySR.getResultObj();
			String info = mySR.getInfo();
						
			try {
				myPerson = personArray[0];
				if (myPerson.getPassword().equals(passwd))
					isValid = true;
			} catch (RuntimeException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			

			
		}
		return isValid;
	}

}
