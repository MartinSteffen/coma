
package coma.handler.impl.db;

import org.apache.log4j.Category;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;
import coma.entities.SearchResult;
import coma.handler.db.InsertService;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari</a>
 * Created on Dec 3, 2004  12:21:37 AM
 */

public class InsertServiceImpl extends Service implements InsertService{

    private static final Category log = Category
    .getInstance(InsertServiceImpl.class.getName());
    
    public InsertServiceImpl(){
        super.init();
    }
    /* (non-Javadoc)
     * @see coma.handler.db.InsertService#insertPerson(coma.entities.Person)
     */
    public SearchResult insertPerson(Person person) {
        // TODO Auto-generated method stub
        return null;
    }

    /* (non-Javadoc)
     * @see coma.handler.db.InsertService#insertReviewReport(coma.entities.ReviewReport)
     */
    public SearchResult insertReviewReport(ReviewReport report) {
        // TODO Auto-generated method stub
        return null;
    }

    /* (non-Javadoc)
     * @see coma.handler.db.InsertService#insertPaper(coma.entities.Paper)
     */
    public SearchResult insertPaper(Paper paper) {
        // TODO Auto-generated method stub
        return null;
    }

    /* (non-Javadoc)
     * @see coma.handler.db.InsertService#insertConference(coma.entities.Conference)
     */
    public SearchResult insertConference(Conference conference) {
        // TODO Auto-generated method stub
        return null;
    }
	/* (non-Javadoc)
	 * @see coma.handler.db.InsertService#insertRating(coma.entities.Rating)
	 */
	public SearchResult insertRating(Rating rating) {
		// TODO Auto-generated method stub
		return null;
	}
	/* (non-Javadoc)
	 * @see coma.handler.db.InsertService#insertCriterion(coma.entities.Criterion)
	 */
	public SearchResult insertCriterion(Criterion criterion) {
		// TODO Auto-generated method stub
		return null;
	}

}
