package coma.handler.util;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Date;
import java.util.Enumeration;
import java.util.regex.Pattern;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.Topic;
import coma.entities.Finish;
import coma.servlet.util.FormParameters;
import coma.servlet.util.SessionAttribs;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de>Mohamed Albari </a>"
 */

public class EntityCreater {

	HttpServletRequest request;

	ResultSet rSet;

	public EntityCreater(HttpServletRequest request) {
		this.request = request;
	}

	public EntityCreater(ResultSet resSet) {
		this.rSet = resSet;
	}

	public EntityCreater() {
	}

	public Conference getConference(HttpServletRequest request) {
		Conference conference = new Conference();
		//TODO
		return conference;
	}

	public Conference getConference(ResultSet resSet) {
		Conference conference = new Conference();
		try {
			
			conference.setAbstract_submission_deadline(resSet.getDate("abstract_submission_deadline"));
			conference.setConference_end(resSet.getDate("conference_end"));
			conference.setConference_start(resSet.getDate("conference_start"));
			conference.setDescription(resSet.getString("description"));
			conference.setFinal_version_deadline(resSet.getDate("final_version_deadline"));
			conference.setHomepage(resSet.getString("homepage"));
			conference.setId(resSet.getInt("id"));
			conference.setMin_review_per_paper(resSet.getInt("min_reviews_per_paper"));
			System.out.println("NAME= \n" + resSet.getString("name"));
			conference.setName(resSet.getString("name"));
			conference.setNotification(resSet.getDate("notification"));
			conference.setPaper_submission_deadline(resSet.getDate("paper_submission_deadline"));
			conference.setReview_deadline(resSet.getDate("review_deadline"));
			
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return conference;
	}
	
	public Person getPerson(ResultSet resSet) {
		Person person = new Person(-1);
		try{
			
		person.setId(resSet.getInt("id"));			
		person.setAffiliation(resSet.getString("affiliation"));
		person.setCity(resSet.getString("city"));
		person.setCountry(resSet.getString("country"));
		person.setEmail(resSet.getString("email"));
		person.setFax_number(resSet.getString("fax_number"));
		person.setFirst_name(resSet.getString("first_name"));
		person.setLast_name(resSet.getString("last_name"));
		person.setPassword(resSet.getString("password"));
		person.setPhone_number(resSet.getString("phone_number"));
		person.setPostal_code(resSet.getString("postal_code"));
		person.setState(resSet.getString("state"));
		person.setStreet(resSet.getString("street"));
		person.setTitle(resSet.getString("title"));
		
		
		}catch (SQLException e) {
			e.printStackTrace();
		}
		return person;
	}
	/**
	 * @author mti
	 * @version 0.1
	 * @param request the form elements
	 * @return the Person submitted by the form
	 * 
	 * consistency check of the form inputs:
	 * - email regex match
	 * - password retpyp match
	 * - last_name not empty 

	 */
	public Person getPerson(HttpServletRequest request)
			throws IllegalArgumentException {

		Person p = new Person(-1);

		if (request.getParameter("last_name").equals("")
				|| request.getParameter("password").equals("")
				|| request.getParameter("password").length()<6)
			throw new IllegalArgumentException("PWshort");

		if (!Pattern.matches("\\b[a-z0-9._%-]+@[a-z0-9._%-]+\\.[a-z]{2,6}\\b",
				request.getParameter("email").toLowerCase()))
			throw new IllegalArgumentException("invalidMail");
		if (!(request.getParameter("password").equals(request
				.getParameter("repassword"))))
			throw new IllegalArgumentException("PWmistyped");

		p.setFirst_name(request.getParameter("first_name"));
		p.setLast_name(request.getParameter("last_name"));
		p.setTitle(request.getParameter("title"));
		p.setAffiliation(request.getParameter("affiliation"));
		p.setEmail(request.getParameter("email"));
		p.setPhone_number(request.getParameter("phone_number"));
		p.setFax_number(request.getParameter("fax_number"));
		p.setStreet(request.getParameter("street"));
		p.setPostal_code(request.getParameter("postal_code"));
		p.setCity(request.getParameter("city"));
		p.setState(request.getParameter("state"));
		p.setCountry(request.getParameter("country"));
		p.setPassword(request.getParameter("password"));

		return p;
	}

	public Paper getPaper(ResultSet resSet) {
		Paper paper = new Paper(-1);
		try {
			
			paper.setAbstract(resSet.getString("abstract"));
			paper.setAuthor_id(resSet.getInt("author_id"));
			paper.setConference_id(resSet.getInt("conference_id"));
			paper.setFilename(resSet.getString("filename"));
			paper.setId(resSet.getInt("id"));
			paper.setLast_edited(resSet.getDate("last_edited"));
			paper.setMim_type(resSet.getString("mime_type"));
			paper.setState(resSet.getInt("state"));
			paper.setTitle(resSet.getString("title"));
			paper.setVersion(resSet.getInt("version"));
			
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return paper;
	}

	/**
	 * FIXME Problem: forms with enctype="multipart/form-data" cannot be read with
	 *  .getParameterNames(); and MultipartRequest stores the file before checking the other inputs
	 * 
	 * @author mti
	 * @version 0.1
	 * <b>This will need the com.oreilly.servlet.* packets</b>
	 * available <a href="http://snert.informatik.uni-kiel.de:8080/~wprguest3/downloads/">here!</a>
	 * @param request the form elements
	 * @return the paper submitted by the form
	 * 
	 * changes:
	 * <ul>
	 * <li> 14.12: getAttribute now uses constants from public class SessionAttribs </li> 
	 * </ul> 
	 * maybe missing error handling
	 * 
	 * consistency check of the form inputs:
	 * - linits the file size to 5 MB
	 * - stores the file on the disk
	 * - renames the filename to a unique one
	 * 
	 */
	public Paper getPaper(HttpServletRequest request) throws IllegalArgumentException {
		Enumeration paramNames = request.getParameterNames();
		HttpSession session = request.getSession(true);
		Paper paper = (Paper) session.getAttribute(SessionAttribs.PAPER);//get an old paper, if existing
		if (paper==null) paper= new Paper(-1); // new Paper
		
		Person theLogedPerson = (Person)session.getAttribute(SessionAttribs.PERSON);
		Conference theConf = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
		String parName = "";
		String parValue = "";
		while(paramNames.hasMoreElements()){
			parName = (String) paramNames.nextElement();
			parValue =request.getParameter(parName);
			if (parValue.equals("")) throw new IllegalArgumentException("Missing Form entry "+parName);
		    }
		paper.setAbstract(request.getParameter(FormParameters.ABSTRACT));
		paper.setAuthor_id(theLogedPerson.getId());
		paper.setVersion(paper.getVersion()+1);
		paper.setLast_edited(new Date());
		paper.setConference_id(theConf.getId());
		//paper.setFilename(""); //set in WriteFile.java
		//paper.setMim_type("");///set in WriteFile.java
		paper.setState(0);
		paper.setTitle(request.getParameter(FormParameters.TITLE));

			
		return paper;
		
	}

	public ReviewReport getReviewReport(ResultSet resSet) {
		ReviewReport report = new ReviewReport();
		
		try {
			report.setConfidental(resSet.getString("confidential"));
			report.setId(resSet.getInt("id"));
			report.setPaperId(resSet.getInt("paper_id"));
			report.setRemarks(resSet.getString("remarks"));
			report.setReviewerId(resSet.getInt("reviewer_id"));
			report.setSummary(resSet.getString("summary"));
			
		} catch (SQLException e) {
			e.printStackTrace();
		}
		
		return report;
	}

	public ReviewReport getReviewReport(HttpServletRequest request) {
		ReviewReport report = new ReviewReport();
		//TODO
		return report;
	}
	
	public Criterion getCriterion(HttpServletRequest request){
		//TODO
		return null;
	}
	
	public Criterion getCriterion(ResultSet resSet){
		Criterion criterion = new Criterion();
		
		try {
			criterion.setConferenceId(resSet.getInt("conference_id"));
			criterion.setDescription(resSet.getString("description"));
			criterion.setId(resSet.getInt("id"));
			criterion.setMaxValue(resSet.getInt("max_value"));
			criterion.setName(resSet.getString("name"));
			criterion.setQualityRating(resSet.getInt("quality_rating"));
			
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return criterion;
	}
	
	public Rating getRating(HttpServletRequest request){
		Rating rating = new Rating();
		//TODO
		return rating;
	}
	
	public Rating getRating(ResultSet resSet){
		Rating rating = new Rating();
		
		try {
			rating.setComment(resSet.getString("comment"));

			rating.setCriterionId(resSet.getInt("criterion_id"));
			rating.setGrade(resSet.getInt("grad"));
			rating.setReviewReportId(resSet.getInt("review_id"));
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return rating;
	}
	
	public Topic getTopic(ResultSet resSet)
	{
		Topic topic = new Topic();
		try
		{
			topic.setId(resSet.getInt("id"));
			topic.setConferenceId(resSet.getInt("conference_id"));
			topic.setName(resSet.getString("name"));
		}
		catch (SQLException e)
		{
			e.printStackTrace();
		}
		return topic;
	}
	
	public Finish getFinish(ResultSet resSet) {
		Finish paper = new Finish();
		try {
			
			paper.setTitle(resSet.getString("title"));
			paper.setAuthor(resSet.getString("last_name"));
			paper.setAvgGrade(resSet.getFloat("avg(Grade)"));
			paper.setTopic(resSet.getString("name"));
			paper.setState(resSet.getInt("state"));
			paper.setPaperId(resSet.getInt("id"));
		
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return paper;
	}
}
