package coma.entities;

import static coma.util.logging.Severity.*;

import coma.servlet.util.XMLHelper;
import coma.handler.db.ReadService;
import java.util.Set;


public class Topic extends Entity {

    private int id=-1;
    private int conference_id;
    private String name;

    public Topic(int id){
	this();
	this.id=id;
    }
    public Topic(){;}

    public int getId(){return id;}
    public void setId(int i){this.id=i;}
    public int getConferenceId(){return conference_id;}
    public void setConferenceId(int i){conference_id=i;}
    public String getName(){return name;}
    public void setName(String s){name=s;}

    public static Topic byId(int i){
	ReadService theRS 
	    = new coma.handler.impl.db.ReadServiceImpl();
	Topic theTopic = new Topic(i);
	SearchCriteria theSC = new SearchCriteria();
	//FIXME: theSC.setTopic(theTopic);
	SearchResult theSR = null;
	//FIXME theSR = theRS.getTopic();
	// FIXME no error handling
	return ((Topic[])theSR.getResultObj())[0];
    }

    public static Set<Topic> allTopics(Conference theConference){
	ReadService theRS 
	    = new coma.handler.impl.db.ReadServiceImpl();
	Topic theTopic = new Topic(-1);
	SearchCriteria theSC = new SearchCriteria();
	theSC.setConference(theConference);
	SearchResult theSR = null; 
	//FIXME theSR =theRS.getTopic();
	// FIXME no error handling
	return new java.util.HashSet<Topic>
	    (java.util.Arrays.asList((Topic[])theSR.getResultObj()));
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
