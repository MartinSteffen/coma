package coma.entities;

import java.util.Set;

import coma.util.logging.ALogger;
import coma.util.logging.Severity;
import static coma.util.logging.Severity.*;
import coma.handler.db.ReadService;
import coma.servlet.util.XMLHelper;

import static java.util.Arrays.asList;

/**
   Wrapper for entries in the DB's Criterion table

   @author ums
 */
public class Criterion extends Entity {

    /* the DB's fields. */
    private Integer id;
    private Integer conferenceId;
    private String name;
    private String description;
    private Integer maxValue;
    private Integer qualityRating;

    /**
       Constructor by id.

       Important: this does _not_ look up the Criterion in the
       database, you need to call a ReadService for that.
     */
    public Criterion(int id){
	this.id = id;
    }

    public int getId(){return id;}
    public int getConferenceId(){return conferenceId;}
    public String getName(){
	/* String is immutable, so we don't have to worry that the
	 * world can modify our field if we return this directly
	 * instead of a clone.
	 */
	return name;
    }
    public String getDescription(){
	/* cf. getName() on why we don't clone.*/
	return description;
    }
    public int getMaxValue(){return maxValue;}
    public int getQualityRating(){return qualityRating;}

    /** 
	read my Conference from the DB. 

	may return null if for some reason there is no Conference.
     */
    public Conference getConference(){

	ReadService rs = new coma.handler.impl.db.ReadServiceImpl();
	SearchCriteria sc = new SearchCriteria();
	sc.setConference(new Conference(getConferenceId()));
	SearchResult sr = rs.getConference(sc);
	
	if (!sr.isSUCCESS()){
	    ALogger.log.log(WARN,
			    "Could not find Conference ",
			    getConferenceId(), "in DB",
			    "for Criterion", getId());
			    
	    return null;
	}

	Set<Conference> cs 
	    = new java.util.HashSet<Conference>(asList((Conference[]) sr.getResultObj()));

	if (cs.size() != 1){
	    ALogger.log.log(WARN, 
			    "I found multiple conferences ",
			    cs,
			    "for Criterion", this);
	}
	for (Conference c: cs){
	    return c;
	}
	ALogger.log.log(INFO, 
			"canthappen:",
			"suddenly a set of conferences is empty in", this);
	return null;
    }

    public StringBuilder toXML(XMLMODE mode){

	switch (mode){

	case SHALLOW:
	    return XMLHelper.tagged("criterion",
				    XMLHelper.tagged("id", ""+getId()),
				    XMLHelper.tagged("name", getName()),
				    XMLHelper.tagged("description", getDescription()),
				    XMLHelper.tagged("conferenceId", ""+getConferenceId()),
				    XMLHelper.tagged("maxValue", ""+getMaxValue()),
				    XMLHelper.tagged("qualityRating", ""+getQualityRating())
				    );
	case DEEP:
	    return XMLHelper.tagged("criterion",
				    XMLHelper.tagged("id", ""+getId()),
				    XMLHelper.tagged("name", getName()),
				    XMLHelper.tagged("description", getDescription()),
				    // XXX not an Entitiy yet getConference().toXML(XMLMODE.SHALLOW),
				    XMLHelper.tagged("maxValue", ""+getMaxValue()),
				    XMLHelper.tagged("qualityRating", ""+getQualityRating())
				    );
	default:
	    coma.util.logging.ALogger.log.log(WARN, 
					      "unknown XMLMODE in",
					      this, ':', mode);
	    return null;
	}
    }
}
