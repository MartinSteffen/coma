package coma.handler.util;

import java.sql.ResultSet;

import javax.servlet.http.HttpServletRequest;

import coma.entities.Conference;


/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de>Mohamed Albari</a>"
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

	public Conference getConference(HttpServletRequest request){
	    Conference conference = new Conference();
	    //TODO
	    return conference;
	}
	public Conference getConference(ResultSet resSet){
	    Conference conference = new Conference();
	    //TODO
	    return conference;
	}
	
}