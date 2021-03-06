package coma.entities;

import java.util.Vector;

import static coma.entities.Entity.XMLMODE.DEEP;
import static coma.entities.Entity.XMLMODE.SHALLOW;
import coma.handler.impl.db.DeleteServiceImpl;
import coma.handler.impl.db.InsertServiceImpl;
import coma.handler.impl.db.ReadServiceImpl;
import coma.servlet.util.User;
import coma.servlet.util.XMLHelper;
import static coma.util.logging.Severity.WARN;
import static java.util.Arrays.asList;

/**
 * @author mti
 */

public class Person extends Entity {
	private ReadServiceImpl myReadService = new ReadServiceImpl();
	private DeleteServiceImpl myDeleteService = new DeleteServiceImpl();
	private InsertServiceImpl myInsertService = new InsertServiceImpl();
	
	private int id;
    private String first_name;
    private String last_name;
    private String title;
    private String affiliation;
    private String email;
    private String phone_number;
    private String fax_number;
    private String street; 
    private String postal_code;
    private String city;
    private String state; 
    private String country; 
    private String password;
    private Vector<Integer> role_type =new Vector<Integer>();
    private int conference_id; //optional

	
	/**
	 * @return Returns the conference_id.
	 */
	public int getConference_id() {
		return conference_id;
	}
	/**
	 * @param conference_id The conference_id to set.
	 */
	public void setConference_id(int conference_id) {
		this.conference_id = conference_id;
	}
	/**
	 * @param id
	 */
	public Person(int id) {
		super();
		this.id = id;
	}
	
	/**
	 * @return Returns the role_type.
	 */
	public int[] getRole_type() {
		int[] role_type_array = new int[role_type.size()];
		for (int i = 0; i < role_type.size(); i++) {
			role_type_array[i]=((Integer)role_type.get(i)).intValue();
		}
		return (role_type_array);
	}
	/**
	 * @param role_type The role_type to set.
	 */public void setRole_type(int new_role_type) {
	 	
	 	this.role_type.add(Integer.valueOf(new_role_type));
	}
	
	public void setRole_type(int[] new_role_type) {
		for (int i = 0; i < new_role_type.length; i++) {
			this.role_type.add(Integer.valueOf(new_role_type[i]));
		}
		
	}
	/**
	 * @return Returns the affiliation.
	 */
	public String getAffiliation() {
		return affiliation;
	}
	/**
	 * @param affiliation The affiliation to set.
	 */
	public void setAffiliation(String affiliation) {
		this.affiliation = affiliation;
	}
	/**
	 * @return Returns the city.
	 */
	public String getCity() {
		return city;
	}
	/**
	 * @param city The city to set.
	 */
	public void setCity(String city) {
		this.city = city;
	}
	/**
	 * @return Returns the country.
	 */
	public String getCountry() {
		return country;
	}
	/**
	 * @param country The country to set.
	 */
	public void setCountry(String country) {
		this.country = country;
	}
	/**
	 * @return Returns the email.
	 */
	public String getEmail() {
		return email;
	}
	/**
	 * @param email The email to set.
	 */
	public void setEmail(String email) {
		this.email = email;
	}
	/**
	 * @return Returns the fax_number.
	 */
	public String getFax_number() {
		return fax_number;
	}
	/**
	 * @param fax_number The fax_number to set.
	 */
	public void setFax_number(String fax_number) {
		this.fax_number = fax_number;
	}
	/**
	 * @return Returns the first_name.
	 */
	public String getFirst_name() {
		return first_name;
	}
	/**
	 * @param first_name The first_name to set.
	 */
	public void setFirst_name(String first_name) {
		this.first_name = first_name;
	}
	/**
	 * @return Returns the id.
	 */
	public int getId() {
		return id;
	}
	/**
	 * @param id The id to set.
	 */
	public void setId(int id) {
		this.id = id;
	}
	/**
	 * @return Returns the last_name.
	 */
	public String getLast_name() {
		return last_name;
	}
	/**
	 * @param last_name The last_name to set.
	 */
	public void setLast_name(String last_name) {
		this.last_name = last_name;
	}
	/**
	 * @return Returns the password.
	 */
	public String getPassword() {
		return password;
	}
	/**
	 * @param password The password to set.
	 */
	public void setPassword(String password) {
		this.password = password;
	}
	/**
	 * @return Returns the phone_number.
	 */
	public String getPhone_number() {
		return phone_number;
	}
	/**
	 * @param phone_number The phone_number to set.
	 */
	public void setPhone_number(String phone_number) {
		this.phone_number = phone_number;
	}
	/**
	 * @return Returns the postal_code.
	 */
	public String getPostal_code() {
		return postal_code;
	}
	/**
	 * @param postal_code The postal_code to set.
	 */
	public void setPostal_code(String postal_code) {
		this.postal_code = postal_code;
	}
	/**
	 * @return Returns the state.
	 */
	public String getState() {
		return state;
	}
	/**
	 * @param state The state to set.
	 */
	public void setState(String state) {
		this.state = state;
	}
	/**
	 * @return Returns the street.
	 */
	public String getStreet() {
		return street;
	}
	/**
	 * @param street The street to set.
	 */
	public void setStreet(String street) {
		this.street = street;
	}
	/**
	 * @return Returns the title.
	 */
	public String getTitle() {
		return title;
	}
	/**
	 * @param title The title to set.
	 */
	public void setTitle(String title) {
		this.title = title;
	}

    public boolean isChair(){
    	return isInRole(role_type, User.CHAIR);
    }
    public boolean isAuthor(){
    	return isInRole(role_type, User.AUTHOR);
    }
    public boolean isReviewer(){
    	return isInRole(role_type, User.REVIEWER);
    }
    public boolean isAdmin(){
    	return isInRole(role_type, User.ADMIN);
    }
	
    private boolean isInRole(Vector roles, int role){
    	boolean result = false;
    	for (int i = 0; i < roles.size(); i++) {
			if(role == ((Integer)roles.get(i)).intValue()){
				result = true;
				break;
			}
		}
    	return result;
    }
	
	
	public Paper[] getPapers() {
		
		Paper mySearchPaper = new Paper(-1);
		mySearchPaper.setAuthor_id(this.id);
		SearchCriteria mysc = new SearchCriteria();
		mysc.setPaper(mySearchPaper);
		SearchResult mySR = myReadService.getPaper(mysc);
		if (mySR != null){
			return (Paper[])mySR.getResultObj();
		}
		
		else {
			
		return null;
		}
	}
	
	public StringBuilder toXML(XMLMODE mode){
	
		switch (mode){
		case DEEP:
		    return XMLHelper.tagged("person",
					    XMLHelper.tagged("id", ""+getId()),
					    XMLHelper.tagged("last_name", getLast_name()),
					    XMLHelper.tagged("first_name", getFirst_name()),
					    XMLHelper.tagged("title", getTitle()),
					    XMLHelper.tagged("affiliation", getAffiliation()),
					    XMLHelper.tagged("email", getEmail()),
					    XMLHelper.tagged("phone_number", getPhone_number()),
						XMLHelper.tagged("fax_number", getFax_number()),
						XMLHelper.tagged("street", getStreet()),
						XMLHelper.tagged("postal_code", getPostal_code()),
						XMLHelper.tagged("city", getCity()),
						XMLHelper.tagged("state", getState()),
					    XMLHelper.tagged("country", getCountry()),
					    XMLHelper.tagged("expertise", 
							     Topic.manyToXML(asList(getPreferredTopics()), 
									     SHALLOW))
						// maybe dangerous XMLHelper.tagged("password", getPassword())
						// FIXME not Entity yet getPapers().toXML(XMLMODE.SHALLOW),
					    // FIXME not Entity yet get???().toXML(XMLMODE.SHALLOW),
					    );
		case SHALLOW:
			 return XMLHelper.tagged("Person",
					    XMLHelper.tagged("id", ""+getId()),
					    XMLHelper.tagged("last_name", getLast_name()),
					    XMLHelper.tagged("first_name", getFirst_name()),
					    XMLHelper.tagged("title", getTitle()),
					    XMLHelper.tagged("affiliation", getAffiliation()),
					    XMLHelper.tagged("email", getEmail()),
					    XMLHelper.tagged("phone_number", getPhone_number()),
						XMLHelper.tagged("fax_number", getFax_number()),
						XMLHelper.tagged("street", getStreet()),
						XMLHelper.tagged("postal_code", getPostal_code()),
						XMLHelper.tagged("city", getCity()),
						XMLHelper.tagged("state", getState()),
						XMLHelper.tagged("country", getCountry())
					    );
		  
		default:
		    coma.util.logging.ALogger.log.log(WARN, 
						      "unknown XMLMODE in",
						      this, ':', mode);
		    return null;
		}
	    }
	
	public Topic[] getPreferredTopics(){
	    //Topic[] result = new Topic[0];
	    SearchResult sr = myReadService.getPreferedTopic(this.id);
	    // if(sr != null){
	    // result = (Topic[])sr.getResultObj();
	    // 	}
	    // return result;
	    int[] tids = (int[])sr.getResultObj();
	    Topic[] result = new Topic[tids.length];
	    for (int j=0; j<tids.length; ++j){
		result[j]= Topic.byId(tids[j], -1);
	    }
	    return result;
	}

	public SearchResult deletePreferredTopic(Topic t){
		return myDeleteService.deletePreferedTopic(this.id, t.getId());
	
	}
	public SearchResult addPreferredTopic(Topic t){
		return myInsertService.prefersTopic(this.id, t.getId());
	}
	
	
}
