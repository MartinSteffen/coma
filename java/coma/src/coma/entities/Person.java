package coma.entities;

/**
 * @author mti
 */

public class Person {
	
	private Integer id;
    private Sting first_name;
    private Sting last_name;
    private Sting title;
    private Sting affiliation;
    private Sting email;
    private Sting phone_number;
    private Sting fax_number;
    private Sting street; 
    private Sting postal_code;
    private Sting city;
    private Sting state; 
    private Sting country; 
    private Sting password;
    
	 public Person(int id){
		this.id=id;
	    }
	 
	 /*getter*/
	 public int getId(){return id;}
	 public String getFirst_name() {return first_name}
	 public String getLast_name() {return last_name}
	 public String getTitle() {return title}
	 public String getAffiliation() {return affiliation}
	 public String getEmail() {return email}
	 public String getPhone_number() {return phone_number}
	 public String getFax_number() {return fax_number}
	 public String getStreet() {return street}
	 public String getPostal_code() {return postal_code}
	 public String getCity() {return city}
	 public String getState() {return state}
	 public String getCountry() {return country}
	 public String getPassword() {return password}
	 
	 /*setter*/
	 
	 
}
