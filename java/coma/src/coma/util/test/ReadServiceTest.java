package coma.util.test;

import junit.framework.TestCase;

import org.apache.log4j.BasicConfigurator;
import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.entities.Topic;
import coma.handler.impl.db.ReadServiceImpl;


/**
 * Created on 16.12.2004
 * <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari</a>
 */

public class ReadServiceTest extends TestCase{

	private Category log = Category.getInstance(ReadServiceTest.class);
	private ReadServiceImpl read = new ReadServiceImpl();
	
	protected void setUp() throws Exception {
		super.setUp();
		BasicConfigurator.configure();
	}
	
	public void testGetConference(){
		boolean run = false;
		if(!run){
			return;
		}
		
		Conference conference = new Conference();
		conference.setId(-1);
		SearchCriteria criteria = new SearchCriteria();
		criteria.setConference(conference);
		
		SearchResult result = read.getConference(criteria);
		Object objResult = result.getResultObj();
		
		Conference[] conferences = (Conference[])objResult;	
		for (int i = 0; i < conferences.length; i++) {
			log.debug(conferences[i].toString());	
		}
	}
	
	public void testGetPerson(){
		boolean run = true;
		if(!run){
			return;
		}
		Person p = new Person(-11);
		p.setFirst_name("Myname0");
		p.setLast_name("Surname0");
		//p.setEmail("test10@web.com");
		int[] role = new int[3];
		role[0] = 0; role[1] = 1; role[2] = 2; 
		p.setRole_type(role);
		SearchCriteria criteria = new SearchCriteria();
		criteria.setPerson(p);
		
		SearchResult result = read.getPerson(criteria);
		Object objResult = result.getResultObj();
		
		Person[] persons = (Person[])objResult;	
		for (int i = 0; i < persons.length; i++) {
			System.out.println(persons[i].toXML());	
		}
		System.out.println("----------------------------");
		
//		result = read.getPersonByRole(role, 1);
//		objResult = result.getResultObj();
//		
//		persons = (Person[])objResult;	
//		for (int i = 0; i < persons.length; i++) {
//			System.out.println(persons[i].toXML());	
//		}
	}
	
	public void testGetPersonByRole(){
		boolean run = false;
		if(!run){
			return;
		}
		int[] role_type = new int[2];
		int conference_id = 1;
		SearchResult result = read.getPersonByRole(role_type, conference_id);
		Object objResult = result.getResultObj();
		Person[] persons = (Person[])objResult;	
		for (int i = 0; i < persons.length; i++) {
			System.out.println(persons[i].toXML());	
		}
	}
	
	public void testGetPaper(){
		boolean run = false;
		if(!run){
			return;
		}
		
		Paper p = new Paper(-2);
		p.setAuthor_id(1);
		//p.setConference_id(1);
		SearchCriteria criteria = new SearchCriteria();
		criteria.setPaper(p);
		
		SearchResult result = read.getPaper(criteria);
		Object objResult = result.getResultObj();
		
		Paper[] papers = (Paper[])objResult;	
		for (int i = 0; i < papers.length; i++) {
			System.out.println(papers[i].getAuthor_id());	
		}
	}
	
	public void testGetReviewReport(){
		boolean run = false;
		if(!run){
			return;
		}
		
		ReviewReport rep = new ReviewReport();
		rep.set_paper_Id(0);
		SearchCriteria criteria = new SearchCriteria();
		criteria.setReviewReport(rep);
		
		SearchResult result = read.getReviewReport(criteria);
		Object objResult = result.getResultObj();
		
		ReviewReport[] reports = (ReviewReport[])objResult;	
		for (int i = 0; i < reports.length; i++) {
			System.out.println(reports[i].toString());	
		}
	}
	
	public void testGetRating(){
		boolean run = false;
		if(!run){
			return;
		}
		
		Rating rat = new Rating();
		SearchCriteria criteria = new SearchCriteria();
		criteria.setRating(rat);
		rat.set_report_id(0);
		SearchResult result = read.getRating(criteria);
		Object objResult = result.getResultObj();
		
		Rating[] ratings = (Rating[])objResult;	
		for (int i = 0; i < ratings.length; i++) {
			System.out.println(ratings[i].toString());	
		}
	}
	
	public void testGetCriterion(){
		boolean run = false;
		if(!run){
			return;
		}
		
		Criterion crit = new Criterion();
		crit.set_conference_id(0);
		crit.setId(0);
		SearchCriteria criteria = new SearchCriteria();
		criteria.setCriterion(crit);
		
		SearchResult result = read.getCriterion(criteria);
		Object objResult = result.getResultObj();
		
		Criterion[] criterions = (Criterion[])objResult;	
		for (int i = 0; i < criterions.length; i++) {
			System.out.println(criterions[i].toXML());	
		}
	}
	
	public void testGetTopic(){
		boolean run = false;
		if(!run){
			return;
		}
		SearchResult result = read.getTopic(0, 1);
		Object objResult = result.getResultObj();
		
		Topic[] topics = (Topic[])objResult;	
		for (int i = 0; i < topics.length; i++) {
			System.out.println(topics[i].toXML());	
		}
	}
	
}
