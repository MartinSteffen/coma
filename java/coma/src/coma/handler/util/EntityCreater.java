package coma.handler.util;

import java.sql.ResultSet;

import javax.servlet.http.HttpServletRequest;

import java.util.regex.*;

import coma.entities.Conference;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.ReviewReport;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de>Mohamed Albari </a>"
 */

public class EntityCreater {

	HttpServletRequest request;

	ResultSet resSet;

	public EntityCreater(HttpServletRequest request) {
		this.request = request;
	}

	public EntityCreater(ResultSet resSet) {
		this.resSet = resSet;
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
		//TODO
		return conference;
	}

	public Person getPerson(ResultSet resSet) {
		Person p = new Person(-1);

		return p;
	}

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
		//TODO
		return paper;
	}

	public Paper getPaper(HttpServletRequest request) {
		Paper paper = new Paper(-1);
		//TODO
		return paper;
	}

	public ReviewReport getReviewReport(ResultSet resSet) {
		ReviewReport report = new ReviewReport();
		//TODO
		return report;
	}

	public ReviewReport getReviewReport(HttpServletRequest request) {
		ReviewReport report = new ReviewReport();
		//TODO
		return report;
	}
}