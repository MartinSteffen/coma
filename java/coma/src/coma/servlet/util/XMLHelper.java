package coma.servlet.util;

import java.io.PrintWriter;

import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.stream.*;


/**
 * @author Peter Kauffels & Mohamed Z. Albari
 * @version 1.0
 */

public class XMLHelper {

	// write to main area
	public String addContent(String msg, StringBuffer result)
	{
		result.append("<content>\n");
		result.append(msg);
		result.append("</content>\n");
		return result.toString();
	}
	
	// write to status-line
	public String addStatus(String msg, StringBuffer result)
	{
		result.append("<status>\n");
		result.append(msg);
		result.append("</status>\n");
		return result.toString();
	}
	
	public String addWarning(String msg, StringBuffer result) 
	{
		result.append("<warning>\n");
		result.append("<message>\n");
		result.append("<![CDATA["+msg+"]]>");
		result.append("</message>\n");
		result.append("</warning>\n");
		return result.toString();
	}

	public String addError(String msg, StringBuffer result) 
	{
		result.append("<error>\n");
		result.append("<message>\n");
		result.append("<![CDATA["+msg+"]]>");
		result.append("</message>\n");
		result.append("</error>\n");
		return result.toString();
	}
	
	/**
	 * Writes a XML-Header.
	 * 
	 * @author mti
	 * 
	 * @param result: destination StringBuffer
	 * @return 
	 */
	public String addXMLHead(StringBuffer result) 
	{
		result.append("<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>\n");
		return result.toString();
	}


    /**
       Alias for tagged, because somebody got it very wrong.
       Not static because the other addXs aren't either, for no reason, either.

       The last entry of c must be a StringBuffer. This is an ugly
       hack. "Normative Kraft des Faktischen", anyone?

       @author ums
     */
    public String addTagged(String tag, CharSequence... c){

	if ((c==null )
	    || (c.length<2)
	    || (!(c[c.length-1] instanceof StringBuffer))){

	    coma.util.logging.ALogger.log.log(coma.util.logging.Severity.ERROR, "addTagged failure!");
	    return null;
	}

	StringBuffer result = (StringBuffer)c[c.length-1];
	CharSequence[] args = new CharSequence[c.length-1];
	System.arraycopy(c, 0, args, 0, c.length-1);

	result.append(tagged(tag, args));
	return result.toString();
    }

    /**
       Puts the content in the tagname.

       If content is not present, creates a <... /> tag.

       This cannot handle attributes. The result is not threadsafe.

       XMLHelper.tagged("foo", "bar", "baz"); gives "<foo>bar baz</foo>",
       XMLHelper.tagged("foo"); gives "<foo />",
       XMLHelper.tagged("foo", XMLHelper.tagged("bar", "baz"));
           gives "<foo><bar>baz</bar></foo>".

       Note that several items in one tag will still be seperated by one single space.   

       2004DEC13: would not actually generate <... /> tags.
       2004DEC13: At request of mti, changed to not provide line breaking or sane spacing.
     */
    public static StringBuilder tagged(String tagname, CharSequence... content){
	StringBuilder result = new StringBuilder();

	if (content == null || content.length==0){

	    // yes, String+String is expensive, but these are short,
	    // so it shouldn't be quite so bad.

	    result.append("<"+tagname+" />");

	} else {
	    assert (content!=null);

	    result.append("<"+tagname+">");
	    for (CharSequence s: content){

		// however, the content might be long, so we don't risk +.
		if (s!=null){ // let's not risk anything.

		    result.append(s);
		    result.append(' ');
		}
	    }
	    result.deleteCharAt(result.length()-1);
	    result.append("</"+tagname+">");
	}
	return result;
    }

	public static void process(StreamSource xmlSource,StreamSource xslSource,PrintWriter out) 
	{
		try 
		{
			TransformerFactory tFactory = TransformerFactory.newInstance();
			Transformer transformer = tFactory.newTransformer(xslSource);
			transformer.transform(xmlSource,new StreamResult(out));
		} 
		catch (Exception e) 
		{
			e.printStackTrace(out);
		}
	}
}
