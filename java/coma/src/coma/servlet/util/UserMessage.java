package coma.servlet.util;

/** Container class for tag names that represent messages to the user.
    This lets us find misspellings at compile time. 

    Note that directly creating user message strings makes it
    impossible to have multi-lingual user interfaces.
 */
public class UserMessage {

    public static final String ERRNOSESSION="<noSession />";
    public static final String ERRDATABASEDOWN="<databaseError />";
    public static final String ERRUNAUTHORIZED="<unauthorized />";
    
    public static final String ERRXMLMODE="<unknownXMLMode />";

    public static final String ALLREPORTSINTRO="<allReportsIntro />";
}
