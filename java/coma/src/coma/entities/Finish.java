package coma.entities;

import coma.servlet.util.XMLHelper;
import coma.util.logging.ALogger;
import static coma.util.logging.Severity.*;

/**
   @author hbra   
*/
public class Finish extends Entity {

    private String title;
    private String author;
    private float avgGrade = -1.0f;
    private String topic;
    private String myTime;
    private int state;
    int Paper_id;
    
    public Finish(){;}

    public String getTitle(){return title;}
    public String getAuthor(){return author;}
    public int getState(){return state;}
    public float getAvgGrade(){return avgGrade;}
    public String  getTopic(){return topic;}
    public int getPaperId(){return Paper_id;}
    public String getmyTime(){return myTime;}
    
    public void setTitle(String title){this.title = title;}
    public void setAuthor(String author){this.author = author;}
    public void setState(int state){this.state = state;}
    public void setAvgGrade(float avgGrade){this.avgGrade = avgGrade;}
    public void setTopic(String topic){this.topic = topic;}
    public void setPaperId(int Paper_id){this.Paper_id = Paper_id;}
    public void setMyTime(String myTime){this.myTime =myTime;}

    public StringBuilder toXML(XMLMODE mode)
    {
		switch (mode) 
		{    
		case DEEP:
		    return XMLHelper.tagged("FINISH",
		    			XMLHelper.tagged("title", ""+title),
		    			XMLHelper.tagged("author", ""+author),
					    XMLHelper.tagged("avggrade", ""+avgGrade),
					    XMLHelper.tagged("topic", ""+topic),
		    			XMLHelper.tagged("state", ""+state),
						XMLHelper.tagged("paper_id", ""+Paper_id),
		    			XMLHelper.tagged("time", ""+myTime));
		case SHALLOW:;
		default:
		    coma.util.logging.ALogger.log.log(WARN, 
						      "unknown XMLMODE in",
						      this, ':', mode);
		    return null;
	}
    }
    


}
