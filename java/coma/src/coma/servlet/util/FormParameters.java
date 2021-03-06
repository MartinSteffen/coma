package coma.servlet.util;

/**
   Container class for names of HTTP Request Parameters that come from
   forms.

   These should not be confused with SessionAttribs that come from the
   HTTP Session. These are the values a user has just filled into the
   form. 

   Special care must be taken to keep these constants in sync with the
   XSLT stylesheets, since those probably need to specify these names
   verbatimly, as well.

   @author ums, but feel free to add entries and modify values (but
   not, of course, the names).
 */
public class FormParameters {
	/** for paper submission*/
	public static final String ABSTRACT="abstract";
	public static final String TITLE="title";
	public static final String TOPICS="topics";
	public static final String PAPER_ID="paper_id";
	
	public static final String EMAIL="email";
	public static final String ACTION="action";
	public static final String PASSWORD="password";
    /** A user-editable ReviewReport attribute */
    public static final String SUMMARY="summary";

    /** A user-editable ReviewReport attribute */
    public static final String REMARKS="remarks";

    /** A user-editable ReviewReport attribute */
    public static final String CONFIDENTAL="confidental";

    /** Identifies different ratings in RatePaper */
    public static final String RATING_PREFIX="ratingForCriterion";
    public static final String RATING_POSTFIX_GRADE="Grade";
    public static final String RATING_POSTFIX_COMMENT="Commt";

    /** A list of Topic-Ids preferred by the current Person */
    public static final String PREFERREDTOPICS="preferredtopics";
    
    public static final String CONFERENCE_ID="conference_id";

    /** for immediate selection of detailed view in ShowReports */
    public static final String REPORTID="reportid";

    /** the checkbox when subscribing */
    public static final String WILLBEAUTHOR="willbeauthor";
}
