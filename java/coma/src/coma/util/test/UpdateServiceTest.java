package coma.util.test;

import java.util.Vector;

import junit.framework.TestCase;

import org.apache.log4j.BasicConfigurator;
import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.entities.Topic;
import coma.handler.impl.db.ReadServiceImpl;
import coma.handler.impl.db.UpdateServiceImpl;

/**
 * Created on 17.12.2004
 * <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari</a>
 */
public class UpdateServiceTest extends TestCase{
	
	private Category log = Category.getInstance(ReadServiceTest.class);
	UpdateServiceImpl update = new UpdateServiceImpl();
	ReadServiceImpl read = new ReadServiceImpl();
	
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
		Paper p = new Paper(100);
		SearchCriteria sc = new SearchCriteria();
		sc.setPaper(p);
		SearchResult sr = read.getPaper(sc);
		p = ((Paper[])sr.getResultObj())[0];
		Integer[] topics = new Integer[2];
		topics[0]=new Integer(6);
		topics[1]=new Integer(7);
		p.setTopics(topics);
		
		sr = update.updatePaper(p);
		System.out.println(sr.getInfo() + sr.SUCCESS);
		
		
	}
	
	public void testUpdatePerson(){
//		Person p = new Person(-1);
//		p.setEmail("max@web.com");
//		p.setLast_name("Mustermann");
//		p.setFirst_name("Max");
//		SearchResult sr = update.updatePerson(p);
//		System.out.println(sr.SUCCESS+ "  "+sr.getInfo());
		
	}
	
	public void testUpdateRating(){
		//TODO 
	}
	
	public void testUpdateReviewReport(){
		//TODO
	}
}
