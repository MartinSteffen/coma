package coma.servlet.util;

/** Container class for tag names that represent messages to the user.
    This lets us find misspellings at compile time. 

    Note that directly creating user message strings makes it
    impossible to have multi-lingual user interfaces.
 */
public class UserMessage {

    /** "[SUBMIT] button" */
    public static final String SUBMITBUTTON="<submit />";



    /** "You do not have a session. Cookie problem? Not logged in?" */
    public static final String ERRNOSESSION="<noSession />";

    /** "The database is not available. Please try again later." */
    public static final String ERRDATABASEDOWN="<databaseError />";

    /** "You are not authorized to do that. Not logged in?" */
    public static final String ERRUNAUTHORIZED="<unauthorized />";
    
    /** "You should get information in a form I do not know. 
	Inform a wizard." */
    public static final String ERRXMLMODE="<unknownXMLMode />";

    /** "These are all reports you are allowed to see." */
    public static final String ALLREPORTSINTRO="<allReportsIntro />";

    /** "These are all papers you can rate." */
    public static final String PAPERS_TO_RATE="<papersToRate />";

    /** "You may now edit your report." */
    public static final String EDITREPORT = "<editReport />";

    /** "Now updateing." */
    public static final String UPDATING = "<updateInProgress />";

    /** "Sorry, failed: the deadline is already over" */
    public static final String ERRTOOLATE="<tooLate />";
}
