package coma.entities;

import java.util.Date;

import static coma.entities.Entity.XMLMODE.DEEP;
import static coma.entities.Entity.XMLMODE.SHALLOW;
import coma.handler.db.ReadService;
import coma.servlet.util.XMLHelper;
import static coma.util.logging.Severity.WARN;
/**
 * @author mal,mti,owu
 *
 */
public class Paper extends Entity {

    int id;
    int conference_id;
    int author_id;
    String title;
    String Abstract;
    Date last_edited = new Date(1l);
    int version = 0;
    String filename;
    int state;
    String mim_type;
    
    public Paper(int id){this.id=id;}
    
    public String getAbstract() {
	System.out.print("&Ab");
        return Abstract;
    }
    public void setAbstract(String abstract1) {
        Abstract = abstract1;
    }
    public int getAuthor_id() {
	System.out.print("&AI");
        return author_id;
    }
    public void setAuthor_id(int author_id) {
        this.author_id = author_id;
    }
    public int getConference_id() {
	System.out.print("&CI");
        return conference_id;
    }
    public void setConference_id(int conference_id) {
        this.conference_id = conference_id;
    }
    public String getFilename() {
	System.out.print("&FN");
        return filename;
    }
    public void setFilename(String filename) {
        this.filename = filename;
    }
    public int getId() {
	System.out.print("&Id");
        return id;
    }
    public void setId(int id) {
        this.id = id;
    }
    public Date getLast_edited() {
	System.out.print("&LE");
	if (last_edited==null)
	    setLast_edited(new Date(1l)); // FIXME FIXME
        return last_edited;
    }
    public void setLast_edited(Date last_edited) {
        this.last_edited = last_edited;
    }
    public String getMim_type() {
	System.out.print("&gMT");
	assert mim_type!=null;
        return mim_type;
    }
    public void setMim_type(String mim_type) {
        this.mim_type = mim_type;
    }
    public int getState() {
	System.out.print("&gS");
        return state;
    }
    public void setState(int state) {
        this.state = state;
    }
    public String getTitle() {
	System.out.print("&gT");
	assert title != null;
        return title;
    }
    public void setTitle(String title) {
        this.title = title;
    }
    public int getVersion() {
	System.out.print("&gV");
        return version;
    }
    public void setVersion(int version) {
        this.version = version;
    }

    public Person getAuthor(){
	System.out.print("&gA");
	ReadService rs = new coma.handler.impl.db.ReadServiceImpl();
	SearchCriteria sc = new SearchCriteria();
	sc.setPerson(new Person(getAuthor_id()));
	SearchResult sr = rs.getPerson(sc);
		System.out.print("getAuthor:"+((Person[])sr.getResultObj())[0]);
	if(sr!=null){
		Person[] personArray = (Person[])sr.getResultObj();
		if (personArray.length>0)
			return ((Person[])sr.getResultObj())[0];
		else
			return null;
			
	}
	else
		return null;
    }

    public StringBuilder toXML(XMLMODE mode){

	try{    	 
		switch (mode){
		case DEEP:
		    return XMLHelper.tagged("paper",
					    XMLHelper.tagged("id", ""+getId()),
					    XMLHelper.tagged("conference_id", ""+getConference_id()),
					    //XMLHelper.tagged("author_id", ""+getAuthor_id()),
					    getAuthor().toXML(XMLMODE.SHALLOW),
					    XMLHelper.tagged("title", ""+getTitle()),
					    XMLHelper.tagged("Abstract", getAbstract()),
					    XMLHelper.tagged("last_edited", getLast_edited().toString()),
					    XMLHelper.tagged("version", getVersion()),
					    XMLHelper.tagged("filename", getFilename()),
					    XMLHelper.tagged("state", ""+getState()),
					    XMLHelper.tagged("mim_type", getMim_type())
						
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
						 XMLHelper.tagged("last_edited", convert_date(getLast_edited())),
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
	} catch (Exception exp){System.out.println("PERSON broken: "+exp); return null;}
	    }
    
    public String convert_date(Date date)
    {
    	if (date!=null)
    	{
	    	StringBuffer result=new StringBuffer();
	    	String sqlDate = date.toString();
	    	result.append(sqlDate.charAt(8));
	    	result.append(sqlDate.charAt(9));
	    	result.append(".");
	    	result.append(sqlDate.charAt(5));
	    	result.append(sqlDate.charAt(6));
	    	result.append(".");
	    	result.append(sqlDate.charAt(0));
	    	result.append(sqlDate.charAt(1));
	    	result.append(sqlDate.charAt(2));
	    	result.append(sqlDate.charAt(3));
	    	return result.toString();
    	}
    	else
    		return null;	
    }
}
