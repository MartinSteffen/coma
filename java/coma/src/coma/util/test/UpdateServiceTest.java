package coma.util.test;

import coma.entities.Conference;
import coma.handler.impl.db.UpdateServiceImpl;

import junit.framework.TestCase;

/*
 * Created on 17.12.2004
 * <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari</a>
 */
public class UpdateServiceTest extends TestCase{
	
	UpdateServiceImpl update = new UpdateServiceImpl();
	
	public void testUpadteConference(){
		Conference conference = new Conference();
		conference.setId(3);
		//TODO set more properties
		update.updateConference(conference);
		
		//TODO do some assertions
	}
	
	public void testUpadteCriterion(){
		//TODO
	}
	
	public void testUpdatePaper(){
		//TODO
	}
	
	public void testUpdatePerson(){
		//TODO
	}
	
	public void testUpdateRating(){
		//TODO 
	}
	
	public void testUpdateReviewReport(){
		//TODO
	}
}
