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
import coma.handler.impl.db.InsertServiceImpl;
import coma.handler.impl.db.ReadServiceImpl;

/**
 * Created on 17.12.2004 <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z.
 * Albari </a>
 */
public class InsertServiceTest extends TestCase {

	private static Category log = Category.getInstance(InsertServiceTest.class);
	InsertServiceImpl insert = new InsertServiceImpl();

	ReadServiceImpl read = new ReadServiceImpl();
	
	protected void setUp() throws Exception {
		super.setUp();
		BasicConfigurator.configure();
	}
	public void testInsertConference() {
		boolean run = false;
		if (run) {
			Conference conference = new Conference();
			conference.setName("Test conference");
			SearchResult sr = insert.insertConference(conference);
			System.out.println(sr.SUCCESS);
		}
		// TODO make some assertions
	}

	public void testInsertCriterion() {
		boolean run = true;
		if (run) {
			Criterion criterion = new Criterion();
			criterion.setConferenceId(9);
			criterion.setName("Test name");
			SearchResult sr = insert.insertCriterion(criterion);
			System.out.println(sr.SUCCESS);
		}
	}

	public void testInsertPaper() {
		boolean run = false;
		if (run) {
			Paper paper = new Paper(-1);
			paper.setConference_id(0);
			paper.setAuthor_id(1);
			paper.setState(1);
			paper.setTitle("This is a test paper");
			SearchResult sr = insert.insertPaper(paper);
			System.out.println(sr.SUCCESS);
		}
	}

	public void testInsertPerson() {
		boolean run = false;
		if (run) {
			Person p = new Person(1);
			p.setFirst_name("Testperson2");
			p.setLast_name("Testperson2");
			p.setEmail("test3@web.com");
			p.setPassword("passwort");
			SearchResult sr = new SearchResult();
			// sr = insert.insertPerson(p);
			// System.out.println(sr.getInfo());
			// System.out.println(sr.SUCCESS);
			SearchCriteria sc = new SearchCriteria();
			sc.setPerson(p);
			sr = read.getPerson(sc);
			Person[] ps = (Person[]) (sr.getResultObj());
			for (int i = 0; i < ps.length; i++) {
				System.out.println(ps[i].toXML());
			}
		}
	}

	public void testInsertReviewReport() {
		boolean run = false;
		if (run) {
			ReviewReport report = new ReviewReport();
			report.setPaperId(1);
			report.setReviewerId(100);
			SearchResult sr = insert.insertReviewReport(report);
			System.out.println(sr.SUCCESS);
		}
	}

	public void testInsertRating() {
		boolean run = false;
		if (run) {
			Rating rating = new Rating();
			rating.set_report_id(0);
			rating.setCriterionId(0);
			rating.setReviewReportId(0);
			SearchResult sr = insert.insertRating(rating);
			System.out.println(sr.SUCCESS);
		}
	}
}
