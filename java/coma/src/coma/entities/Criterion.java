package coma.entities;


import java.util.Set;


import coma.util.logging.ALogger;


import coma.handler.db.ReadService;
import coma.servlet.util.XMLHelper;

import static coma.util.logging.Severity.*;


import static java.util.Arrays.asList;



/**
   Wrapper for entries in the DB's Criterion table

   @author ums, mal
*/
public class Criterion extends Entity {

    /* the DB's fields. */
    private int id = -1;
    private int conferenceId = -1;
    private String name;
    private String description;
    private int maxValue = -1;
    private int qualityRating = -1;

    /**
       Constructor by id.

       Important: this does _not_ look up the Criterion in the
       database, you need to call a ReadService for that.
    */
    public Criterion(int id){
	this.id = id;
    }

    public Criterion(){;}

    public int getId(){return id;}
    public int getConferenceId(){return conferenceId;}
    public int get_conference_id(){return getConferenceId();}

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
    public int get_max_value(){return getMaxValue();}

    public int getQualityRating(){return qualityRating;}
    public int get_quality_rating(){return getQualityRating();}

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
				    // XXX maybe don't need Conference at all here?
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
    
    
    public void setConferenceId(int conferenceId) {
	this.conferenceId = conferenceId;
    }
    public void set_conference_id(int c){setConferenceId(c);}

    public void setDescription(String description) {
	this.description = description;
    }

    public void setId(int id) {
	this.id = id;
    }

    public void setMaxValue(int maxValue) {
	this.maxValue = maxValue;
    }
    public void set_max_value(int m){setMaxValue(m);}

    public void setName(String name) {
	this.name = name;
    }

    public void setQualityRating(int qualityRating) {
	this.qualityRating = qualityRating;
    }
    public void set_quality_rating(int qr){setQualityRating(qr);}

    
}
