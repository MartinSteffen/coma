package coma.entities;

import java.util.HashSet;
import java.util.Set;

// import static coma.entities.Entity.XMLMODE.*;
// import static coma.entities.Entity.XMLMODE.SHALLOW;
import coma.handler.db.ReadService;
import coma.servlet.util.XMLHelper;
import static coma.util.logging.Severity.WARN;


public class Topic extends Entity {

    private int id=-1;
    private int conference_id;
    private String name;

    private static ReadService theRS 
	= new coma.handler.impl.db.ReadServiceImpl();

    public Topic(int id){
	this();
	this.id=id;
    }
    public Topic(){super();}

    public int getId(){return id;}
    public void setId(int i){this.id=i;}
    public int getConferenceId(){return conference_id;}
    public void setConferenceId(int i){conference_id=i;}
    public String getName(){return name;}
    public void setName(String s){name=s;}

    public static Topic byId(int i, int confid){
	Topic theTopic = new Topic(i);
	SearchResult theSR = null;
	theSR = theRS.getTopic(i, confid);
	try {
	    return ((Topic[])theSR.getResultObj())[0];
	} catch (ClassCastException cce) {
	    coma.util.logging.ALogger.log.log(WARN, 
					      "class cast in Topic.allTopics?",
					      cce.toString());
	    return null;
	}
    }

    public static Set<Topic> allTopics(Conference theConference){
	SearchResult theSR = null; 
	theSR =theRS.getTopic(-1, theConference.getId());
	try {
	    return new java.util.HashSet<Topic>
		(java.util.Arrays.asList((Topic[])theSR.getResultObj()));
	} catch (ClassCastException cce){
	    coma.util.logging.ALogger.log.log(WARN, 
					      "class cast in Topic.allTopics?",
					      cce.toString());
	    return new java.util.HashSet<Topic>();
	}
    }

    public StringBuilder toXML(XMLMODE mode){
		switch (mode){
		case DEEP:
		    //fall through, deep doesn't make sense
		case SHALLOW:
		    return XMLHelper.tagged("topic",
					    XMLHelper.tagged("id", getId()),
					    XMLHelper.tagged("name", getName()));
		default:
		    coma.util.logging.ALogger.log.log(WARN, 
						      "unknown XMLMODE in",
						      this, ':', mode);
		    return null;
		}
    }

}
