package coma.entities;

import static coma.util.logging.Severity.*;

import coma.servlet.util.XMLHelper;
import coma.handler.impl.db.ReadServiceImpl;


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

    public StringBuilder toXML(XMLMODE mode){
		switch (mode){
		case DEEP:
		    //fall through, deep doesn't make sense
		case SHALLOW:
		    return XMLHelper.tagged("Topic",
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
