package coma.entities;

import java.util.Date;
import static coma.util.logging.Severity.*;
import coma.servlet.util.XMLHelper;
/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de>Mohamed Z. Albari</a>
 *
 */
public class Paper extends Entity {

    int id;
    int conference_id;
    int author_id;
    String title;
    String Abstract;
    Date last_edited;
    int version;
    String filename;
    int state;
    String mim_type;
    
    public Paper(int id){this.id=id;}
    
    public String getAbstract() {
        return Abstract;
    }
    public void setAbstract(String abstract1) {
        Abstract = abstract1;
    }
    public int getAuthor_id() {
        return author_id;
    }
    public void setAuthor_id(int author_id) {
        this.author_id = author_id;
    }
    public int getConference_id() {
        return conference_id;
    }
    public void setConference_id(int conference_id) {
        this.conference_id = conference_id;
    }
    public String getFilename() {
        return filename;
    }
    public void setFilename(String filename) {
        this.filename = filename;
    }
    public int getId() {
        return id;
    }
    public void setId(int id) {
        this.id = id;
    }
    public Date getLast_edited() {
        return last_edited;
    }
    public void setLast_edited(Date last_edited) {
        this.last_edited = last_edited;
    }
    public String getMim_type() {
        return mim_type;
    }
    public void setMim_type(String mim_type) {
        this.mim_type = mim_type;
    }
    public int getState() {
        return state;
    }
    public void setState(int state) {
        this.state = state;
    }
    public String getTitle() {
        return title;
    }
    public void setTitle(String title) {
        this.title = title;
    }
    public int getVersion() {
        return version;
    }
    public void setVersion(int version) {
        this.version = version;
    }
    public StringBuilder toXML(XMLMODE mode){
    	 
		switch (mode){
		case DEEP:
		    return XMLHelper.tagged("paper",
					    XMLHelper.tagged("id", ""+getId()),
					    XMLHelper.tagged("conference_id", ""+getConference_id()),
					    XMLHelper.tagged("author_id", ""+getAuthor_id()),
					    XMLHelper.tagged("title", ""+getTitle()),
					    XMLHelper.tagged("Abstract", getAbstract()),
					    XMLHelper.tagged("last_edited", getLast_edited().toString()),
					    XMLHelper.tagged("version", ""+getVersion()),
						XMLHelper.tagged("filename", ""+getFilename()),
						XMLHelper.tagged("state", ""+getState()),
						XMLHelper.tagged("mim_type", ""+getMim_type())
						
						// FIXME not Entity yet getCoAuthors().toXML(XMLMODE.SHALLOW),
					    // FIXME not Entity yet get???().toXML(XMLMODE.SHALLOW),
					    );
		case SHALLOW:
			 return XMLHelper.tagged("paper",
						 XMLHelper.tagged("id", ""+getId()),
						 XMLHelper.tagged("conference_id", ""+getConference_id()),
						 XMLHelper.tagged("author_id", ""+getAuthor_id()),
						 XMLHelper.tagged("title", ""+getTitle()),
						 XMLHelper.tagged("Abstract", getAbstract()),
						 XMLHelper.tagged("last_edited", getLast_edited().toString()),
						 XMLHelper.tagged("version", ""+getVersion()),
						 XMLHelper.tagged("filename", ""+getFilename()),
						 XMLHelper.tagged("state", ""+getState()),
						 XMLHelper.tagged("mim_type", ""+getMim_type())
					    );
		  
		default:
		    coma.util.logging.ALogger.log.log(WARN, 
						      "unknown XMLMODE in",
						      this, ':', mode);
		    return null;
		}
	    }
}
