package coma.util.test;

import java.sql.ResultSet;
import java.sql.SQLException;

import junit.framework.TestCase;
import coma.entities.Conference;
import coma.entities.Person;
import coma.entities.SearchCriteria;
import coma.entities.SearchResult;
import coma.handler.impl.db.InsertServiceImpl;
import coma.handler.impl.db.ReadServiceImpl;

/**
 * Created on 17.12.2004 <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z.
 * Albari </a>
 */
public class InsertServiceTest extends TestCase {

	InsertServiceImpl insert = new InsertServiceImpl();

	ReadServiceImpl read = new ReadServiceImpl();

	public void testInsertConference() {
		boolean run = true;
		if (run) {
			Conference conference = new Conference();
			conference.setName("Test conference");
			SearchResult sr = insert.insertConference(conference);
			System.out.println(sr.SUCCESS);
		}
		// TODO make some assertions
	}

	public void testInsertCriterion() {
		// TODO
	}

	public void testInsertPaper() {
		// TODO
	}

	public void testInsertPerson() {
		boolean run = false;
		if(run){
			Person p = new Person(1);
			p.setFirst_name("Testperson2");
			p.setLast_name("Testperson2");
			p.setEmail("test3@web.com");
			p.setPassword("passwort");
			SearchResult sr = new SearchResult(); 
			//sr = insert.insertPerson(p);
			//System.out.println(sr.getInfo());
			//System.out.println(sr.SUCCESS);
			SearchCriteria sc = new SearchCriteria();
			sc.setPerson(p);
			sr = read.getPerson(sc);
			Person[] ps = (Person[])(sr.getResultObj());
			for (int i = 0; i < ps.length; i++) {
				System.out.println(ps[i].toXML());
			}
		}
	}

	public void testInsertRating() {
		// TODO
	}

	public void testInsertReviewReport() {
		// TODO
	}
}
