package coma.servlet.servlets;


import java.io.PrintWriter;
import java.io.StringReader;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.xml.transform.stream.StreamSource;



import coma.entities.Conference;
import coma.entities.Person;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.handler.impl.db.ReadServiceImpl;
import coma.servlet.util.FormParameters;
import coma.servlet.util.Navcolumn;
import coma.servlet.util.SessionAttribs;
import coma.servlet.util.XMLHelper;

/**
 * @author mti & owu
 */
public class Login extends HttpServlet {

	static enum ACTIONS {LOGIN, LOGOUT};
	private ReadServiceImpl myReadService = new ReadServiceImpl();
	private Person myPerson = null;
	private Conference myConference = new Conference(-1);
	
	public void doGet(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, java.io.IOException {

		HttpSession session;
		session = request.getSession(true);
		String email = request.getParameter(FormParameters.EMAIL);
		String passwd = request.getParameter(FormParameters.PASSWORD);
		
		ACTIONS action = ACTIONS.LOGIN;
		
		
		try {
			action = ACTIONS.valueOf(request.getParameter(FormParameters.ACTION).toUpperCase());
		} catch (RuntimeException e) {
				// TODO Auto-generated catch block
			// no action selected => do the LOGIN action
			//e.printStackTrace();
		}
		
		StringBuffer result = new StringBuffer();
		XMLHelper helper = new XMLHelper();

		String path = getServletContext().getRealPath("");
		String xslt = path + "/style/xsl/index.xsl";
		PrintWriter out = response.getWriter();

		helper.addXMLHead(result);
		result.append("<login>\n");
		
		switch (action) {
		case LOGIN: 
		int conference_id=-1;
		try {
			conference_id = Integer.parseInt(request.getParameter(FormParameters.CONFERENCE_ID));
		} catch (NumberFormatException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		

		if ((email == null || email.equals(""))
				|| (passwd == null || passwd.equals(""))) {
			session.setAttribute(SessionAttribs.PERSON, null);
			result.append(XMLHelper.tagged("form_error"));
		}

		else {
			if (validatePasswd(email, passwd, conference_id)) {

				session.setAttribute(SessionAttribs.PERSON, myPerson);
				
				SearchCriteria mysc = new SearchCriteria();
				mysc.setConference(myConference);
				SearchResult mySR = myReadService.getConference(mysc);
				if (mySR != null){
					Conference[] conferencesArray = (Conference[]) mySR.getResultObj();
					String info = mySR.getInfo();
					if(conferencesArray.length == 1){			
						myConference = conferencesArray[0];
						session.setAttribute(SessionAttribs.CONFERENCE, myConference);	
						result.append(XMLHelper.tagged("success",myPerson.toXML()));
					}
					else {
						session.setAttribute(SessionAttribs.PERSON, null);
						result.append(XMLHelper.tagged("conf_not_found",conference_id));
					}
				}
				else{
					session.setAttribute(SessionAttribs.PERSON, null);
					result.append(XMLHelper.tagged("conf_not_found",conference_id));
					}
				
				
				
				
				
			}

			else {
				session.setAttribute(SessionAttribs.PERSON, null);
				result.append(XMLHelper.tagged("password_error",email+passwd));
			}

		}
		break;
		case LOGOUT:
			session.invalidate();
			session = request.getSession(true);
			response.sendRedirect("/coma/index.html");
			break;
		default:
			
			break;
		}	
		Navcolumn myNavCol = new Navcolumn(session);
		result.append(myNavCol.toString());
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

	private boolean validatePasswd(String email, String passwd, int conference_id) {

		boolean isValid = false;

		
		Person mySearchPerson = new Person(-1);
		mySearchPerson.setEmail(email);
		SearchCriteria mysc = new SearchCriteria();
		mysc.setPerson(mySearchPerson);
		SearchResult mySR = myReadService.getPerson(mysc);
		if (mySR != null){
			Person[] personArray = (Person[]) mySR.getResultObj();
			String info = mySR.getInfo();
			if(personArray.length == 1){			
				myPerson = personArray[0];
				if (myPerson.getPassword().equals(passwd)){
					isValid = true;
				}
				mySR = myReadService.getPersonRoles(conference_id, myPerson.getId());
				if( mySR != null){
					int[] roles = (int[])mySR.getResultObj();
					myPerson.setRole_type(roles);
				}
			}

			
		}
				
		return isValid;
	}

}
