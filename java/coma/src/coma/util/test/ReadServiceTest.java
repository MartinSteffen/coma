package coma.util.test;

import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.handler.impl.db.ReadServiceImpl;

import junit.framework.TestCase;


/*
 * Created on 16.12.2004
 * <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari</a>
 */

public class ReadServiceTest extends TestCase{

	private Category log = Category.getInstance(ReadServiceTest.class);
	private ReadServiceImpl read = new ReadServiceImpl();
	
	public void testGetConference(){
		boolean run = false;
		if(!run){
			return;
		}
		
		Conference conference = new Conference();
		conference.setId(1);
		SearchCriteria criteria = new SearchCriteria();
		criteria.setConference(conference);
		
		SearchResult result = read.getConference(criteria);
		Object objResult = result.getResultObj();
		
		Conference[] conferences = (Conference[])objResult;	
		for (int i = 0; i < conferences.length; i++) {
			System.out.println(conferences[i].toString());	
		}
	}
	
	public void testGetPerson(){
		boolean run = false;
		if(!run){
			return;
		}
		
		Person p = new Person(1);
		//p.setLast_name("Susi");
		SearchCriteria criteria = new SearchCriteria();
		criteria.setPerson(p);
		
		SearchResult result = read.getPerson(criteria);
		Object objResult = result.getResultObj();
		
		Person[] persons = (Person[])objResult;	
		for (int i = 0; i < persons.length; i++) {
			System.out.println(persons[i].toString());	
		}
	}
	
	public void testGetPaper(){
		boolean run = false;
		if(!run){
			return;
		}
		
		Paper p = new Paper(1);
		//p.setAuthor_id(2);
		SearchCriteria criteria = new SearchCriteria();
		criteria.setPaper(p);
		
		SearchResult result = read.getPaper(criteria);
		Object objResult = result.getResultObj();
		
		Paper[] papers = (Paper[])objResult;	
		for (int i = 0; i < papers.length; i++) {
			System.out.println(papers[i].toString());	
		}
	}
	
	public void testGetReviewReport(){
		boolean run = true;
		if(!run){
			return;
		}
		
		ReviewReport rep = new ReviewReport();
		
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
		SearchCriteria criteria = new SearchCriteria();
		criteria.setCriterion(crit);
		
		SearchResult result = read.getCriterion(criteria);
		Object objResult = result.getResultObj();
		
		Criterion[] criterions = (Criterion[])objResult;	
		for (int i = 0; i < criterions.length; i++) {
			System.out.println(criterions[i].toString());	
		}
	}
	
}
