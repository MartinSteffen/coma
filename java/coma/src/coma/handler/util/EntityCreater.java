package coma.handler.util;

import java.io.IOException;
import java.sql.ResultSet;
import java.sql.SQLException;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import java.util.Enumeration;
import java.util.regex.*;

import com.oreilly.servlet.MultipartRequest;
import com.oreilly.servlet.multipart.DefaultFileRenamePolicy;


import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;

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
			conference.setMin_review_per_paper(resSet.getInt("min_review_per_paper"));
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
			
		person.setAffiliation(resSet.getString("affilication"));
		person.setCity(resSet.getString("city"));
		person.setCountry(resSet.getString("country"));
		person.setEmail(resSet.getString("email"));
		person.setFax_number(resSet.getString("fax_number"));
		person.setFirst_name(resSet.getString("first_name"));
		person.setId(resSet.getInt("id"));
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
			throw new IllegalArgumentException();

		if (!Pattern.matches("\\b[a-z0-9._%-]+@[a-z0-9._%-]+\\.[a-z]{2,6}\\b",
				request.getParameter("email").toLowerCase()))
			throw new IllegalArgumentException();
		if (!(request.getParameter("password").equals(request
				.getParameter("repassword"))))
			throw new IllegalArgumentException();

		p.setFirst_name(request.getParameter("first_name"));
		p.setLast_name(request.getParameter("last_name"));
		p.setTitle(request.getParameter("title"));
		p.setAffiliation(request.getParameter("affiliation"));
		p.setEmail(request.getParameter("email"));
		p.setPhone_number(request.getParameter("phone_number"));
		p.setFax_number(request.getParameter("Fax_number"));
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
			paper.setMim_type(resSet.getString("mim_type"));
			paper.setState(resSet.getInt("state"));
			paper.setTitle(resSet.getString("title"));
			paper.setVersion(resSet.getInt("version"));
			
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return paper;
	}

	/**
	 * @author mti
	 * @version 0.1
	 * <b>This will need the com.oreilly.servlet.* packets</b>
	 * available <a href="http://snert.informatik.uni-kiel.de:8080/~wprguest3/downloads/">here!</a>
	 * @param request the form elements
	 * @return the paper submitted by the form
	 * 
	 * maybe missing error handling
	 * 
	 * consistency check of the form inputs:
	 * - linits the file size to 5 MB
	 * - stores the file on the disk
	 * - renames the filename to a unique one
	 * 
	 */
	public Paper getPaper(HttpServletRequest request) throws IllegalArgumentException, IOException {
		Paper paper = new Paper(-1); // new Paper
		// path where the paper is stored
		String webTempPath = request.getContextPath()+"/papers";
		// limit the size and store the file
		MultipartRequest mpr = new MultipartRequest(request,webTempPath,(5*1024*1024),new DefaultFileRenamePolicy());
		//get file and parameter names
		Enumeration paramNames = request.getParameterNames();
		while (paramNames.hasMoreElements()) {
			if (request.getParameter((String)paramNames.nextElement()).equals(""))
				throw new IllegalArgumentException();
			
		}
		Enumeration fileNames = mpr.getFileNames();
		//
		String theSystemFileName = mpr.getFilesystemName((String) fileNames.nextElement());
		
		// get session attributes
		HttpSession session= request.getSession(true);
		Person theAuthor = (Person) session.getAttribute("person");
		Conference theConference = (Conference) session.getAttribute("conference");
		
		// set the paper attributes
		paper.setAbstract(request.getParameter("abstract"));
		paper.setAuthor_id(theAuthor.getId());
		paper.setConference_id(theConference.getId());
		paper.setFilename(theSystemFileName);
		paper.setMim_type(mpr.getContentType(theSystemFileName));
		paper.setState(-1);
		paper.setTitle(request.getParameter("title"));
				
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
}