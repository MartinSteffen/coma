package coma.util.test;

import junit.framework.TestCase;

import org.apache.log4j.BasicConfigurator;
import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Person;
import coma.entities.SearchResult;
import coma.handler.impl.db.UpdateServiceImpl;

/**
 * Created on 17.12.2004
 * <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari</a>
 */
public class UpdateServiceTest extends TestCase{
	
	private Category log = Category.getInstance(ReadServiceTest.class);
	UpdateServiceImpl update = new UpdateServiceImpl();
	
	protected void setUp() throws Exception {
		super.setUp();
		BasicConfigurator.configure();
	}
	public void testUpadteConference(){
		Conference conference = new Conference();
		conference.setId(3);
		//TODO set more properties
		//update.updateConference(conference);
		
		//TODO do some assertions
	}
	
	public void testUpadteCriterion(){
		//TODO
	}
	
	public void testUpdatePaper(){
		//TODO
	}
	
	public void testUpdatePerson(){
		Person p = new Person(126);
		p.setLast_name("Mustermann126");
		p.setFirst_name("Max126");
		SearchResult sr = update.updatePerson(p);
		System.out.println(sr.SUCCESS+ "  "+sr.getInfo());
		
	}
	
	public void testUpdateRating(){
		//TODO 
	}
	
	public void testUpdateReviewReport(){
		//TODO
	}
}
