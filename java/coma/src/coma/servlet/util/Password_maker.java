/*
 * Created on 11.12.2004
 *
 * TODO To change the template for this generated file go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
package coma.servlet.util;

/**
 * @author superingo
 *
 * TODO To change the template for this generated type comment go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
public class Password_maker {
    private String first,last,email;
    
    public Password_maker(String first_name ,String last_name,String e_mail)
    {
        first = first_name;
        last = last_name;
        email = e_mail;
    }
    
    public String generate_password()
    {
        String pass = "" + first.toLowerCase().charAt(0) + last.toLowerCase().charAt(0) + last.toLowerCase().charAt(1);
        return pass;
    }
    

}
