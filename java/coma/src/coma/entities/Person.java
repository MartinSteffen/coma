package coma.entities;

import static coma.util.logging.Severity.*;

import coma.servlet.util.XMLHelper;
import coma.handler.impl.db.ReadServiceImpl;

/**
 * @author mti
 */

public class Person extends Entity {
	
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
    private int role_type;

	
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
	public int getRole_type() {
		return role_type;
	}
	/**
	 * @param role_type The role_type to set.
	 */
	public void setRole_type(int role_type) {
		this.role_type = role_type;
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
	
	public Paper[] getPapers() throws Exception{
		
		ReadServiceImpl myReadService = new ReadServiceImpl();
		Paper mySearchPaper = new Paper(-1);
		mySearchPaper.setAuthor_id(this.id);
		SearchCriteria mysc = new SearchCriteria();
		mysc.setPaper(mySearchPaper);
		SearchResult mySR = myReadService.getPaper(mysc);
		if (!mySR.isSUCCESS())
			throw new Exception(mySR.getInfo());
		else {
		} 
		return (Paper[])mySR.getResultObj();
		
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
						XMLHelper.tagged("country", getCountry())
						// maybe dangerous XMLHelper.tagged("password", getPassword())
						// FIXME not Entity yet getPapers().toXML(XMLMODE.SHALLOW),
					    // FIXME not Entity yet get???().toXML(XMLMODE.SHALLOW),
					    );
		case SHALLOW:
			 return XMLHelper.tagged("Person",
			 			XMLHelper.tagged("id", ""+getId()),
						XMLHelper.tagged("last_name", getLast_name()),
						XMLHelper.tagged("email", getEmail())
					    );
		  
		default:
		    coma.util.logging.ALogger.log.log(WARN, 
						      "unknown XMLMODE in",
						      this, ':', mode);
		    return null;
		}
	    }
}
