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
    /** to request the Conference object associated with the used conference. */
    public static final String CONFERENCE="conference";
    /** to request the user's login name */
    @Deprecated
    public static final String USER="user";

    /** is LOGIN_OK if login is ok. (duh!) */
    /** mti,owu no longer used: the session has a person, or not*/
    @Deprecated
    public static final String LOGIN="login";
    @Deprecated
    public static final String LOGIN_OK="ok";

    // was used in RatePaper. Now using hidden form and PageStateHelper
    @Deprecated
    public static final String RATEPAPER_STATE="rpstate";

    /** the ID of the paper that is currently important (e.g. about to be rated) */
    public static final String PAPERID="paperid";
    /** the Paper that is currently important. Not sure if this will be used. */
    public static final String PAPER="paper";
    public static final String PAPER_ARRAY="paper_array";
    /** the Review Report ID that is currently important */
    public static final String REPORTID="reportid";
    /** the Review Report that is currently important */
    public static final String REPORT="report";

}
