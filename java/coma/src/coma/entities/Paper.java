package coma.entities;

import java.util.Date;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de>Mohamed Z. Albari</a>
 *
 */
public class Paper {

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
}
