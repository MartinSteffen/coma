package coma.servlet.util;

/**
   Container for string constants that are used as Session Attribute
   names. 

   Having these here lets us find misspeelings (sic!) of session
   attributes at compile time and not only at run time.

   @author ums
 */
public class SessionAttribs {

    /** to request the Person object associated with the logged-in user. */
    public static final String PERSON="person";

    /** to request the user's login name */
    public static final String USER="user";

    /** is LOGIN_OK if login is ok. (duh!) */
    public static final String LOGIN="login";
    public static final String LOGIN_OK="ok";

    public static final String RATEPAPER_STATE="rpstate";
}
