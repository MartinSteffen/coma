package coma.util.test;

import junit.framework.TestCase;
import coma.entities.Conference;
import coma.handler.impl.db.InsertServiceImpl;
import coma.handler.impl.db.ReadServiceImpl;

/**
 * Created on 17.12.2004
 * <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari</a>
 */
public class InsertServiceTest extends TestCase{
	
	InsertServiceImpl insert = new InsertServiceImpl();
    ReadServiceImpl read = new ReadServiceImpl();
    
	public void testInsertConference(){
		Conference conference = new Conference();
		conference.setName("Test conference");
		//TODO: set other properties
		
		insert.insertConference(conference);
		//TODO make some assertions
	}
	
	public void testInsertCriterion(){
		//TODO
	}
	
	public void testInsertPaper(){
		//TODO
	}
	
	public void testInsertPerson(){
		//TODO
	}
	
	public void testInsertRating(){
		//TODO
	}
	public void testInsertReviewReport(){
		//TODO
	}
}
