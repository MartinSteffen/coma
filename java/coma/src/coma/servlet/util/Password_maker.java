/*
 * Created on 11.12.2004
 *
 * TODO To change the template for this generated file go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
package coma.servlet.util;
import java.util.*;

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
        String first_low = first.toLowerCase();
        int a = first_low.charAt(first_low.length()-1);
        int b = first_low.charAt(0);
        String last_low = last.toLowerCase();
        int c = last_low.charAt(last_low.length()-1);
        int d = last_low.charAt(0);
    	String email_low = email.toLowerCase();
    	int e = email_low.charAt(last_low.length()-1);
        int f = email_low.charAt(0);
    	a = (a + 4)%25+97;
    	b = (b - 6)%25+97;
    	c = (b - 10)%25+97;
    	d = (b + 2)%25+97;
    	e = (b + 17)%25+97;
    	f = (b - 6)%25+97;
    	Random r = new Random();
    	int g = r.nextInt(10);
    	int h = r.nextInt(10);
    	String pass = String.valueOf((char)a) + String.valueOf(g) + String.valueOf((char)b) + String.valueOf((char)c) + String.valueOf((char)d) + String.valueOf((char)e) + String.valueOf((char)f) + String.valueOf(h);

	coma.util.logging.ALogger.log.log(coma.util.logging.Severity.INFO, 
					  "password for", email,
					  "is",pass);
        return pass;
    }
    

}
