package coma.util.test;

import junit.framework.TestCase;

import org.apache.log4j.BasicConfigurator;
import org.apache.log4j.Category;

import coma.entities.SearchResult;
import coma.handler.impl.db.DeleteServiceImpl;

/**
 * Created on 19.01.2005 
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari </a>
 */
public class DeleteServiceTest extends TestCase {

	private static Category log = Category.getInstance(DeleteServiceTest.class);

	DeleteServiceImpl deleteService = new DeleteServiceImpl();

	/*
	 * @see TestCase#setUp()
	 */
	protected void setUp() throws Exception {
		super.setUp();
		BasicConfigurator.configure();
	}

	public void testDeleteConference() {
		SearchResult sr = deleteService.deleteConference(3);
		if (sr != null) {
			log.debug(sr.getInfo());
			log.debug("deleting conference was successful=" + sr.SUCCESS);
		}
	}

	public void testDeletePerson() {
		SearchResult sr = deleteService.deletePerson(3);
		if (sr != null) {
			log.debug(sr.getInfo());
			log.debug("deleting person was successful=" + sr.SUCCESS);
		}
	}

	public void testDeletePaper() {
		SearchResult sr = deleteService.deletePaper(3);
		if (sr != null) {
			log.debug(sr.getInfo());
			log.debug("deleting paper was successful=" + sr.SUCCESS);
		}
	}

	public void testDeleteCriterion() {
		SearchResult sr = deleteService.deleteCriterion(3);
		if (sr != null) {
			log.debug(sr.getInfo());
			log.debug("deleting criterion was successful=" + sr.SUCCESS);
		}
	}

	public void testDeleteReviewReport() {
		SearchResult sr = deleteService.deleteReviewReport(3);
		if (sr != null) {
			log.debug(sr.getInfo());
			log.debug("deleting review report was successful=" + sr.SUCCESS);
		}
	}

	public void testDeleteTopic() {
		SearchResult sr = deleteService.deleteTopic(3);
		if (sr != null) {
			log.debug(sr.getInfo());
			log.debug("deleting topic was successful=" + sr.SUCCESS);
		}
	}

}
