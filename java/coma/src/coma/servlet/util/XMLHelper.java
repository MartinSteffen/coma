package coma.servlet.util;

import java.io.PrintWriter;

import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.stream.StreamResult;
import javax.xml.transform.stream.StreamSource;


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

       2005JAN11 ums: added cast now needed by tagged(Object...)
       2005JAN11 ums: added big warning so that the fools will maybe not
                      call this anymore.

       @author ums
       @deprecated should not have been used in the first place.
       Somebody had called this without providing it and commited
       non-compileable source.
     */
    @Deprecated
    public String addTagged(String tag, CharSequence... c){

	if ((c==null )
	    || (c.length<1)
	    || (!(c[c.length-1] instanceof StringBuffer))){

	    coma.util.logging.ALogger.log.log(coma.util.logging.Severity.ERROR, "addTagged failure!");
	    return null;
	}

	StringBuffer result = (StringBuffer)c[c.length-1];
	CharSequence[] args = new CharSequence[c.length-1];
	System.arraycopy(c, 0, args, 0, c.length-1);

	result.append(tagged(tag, (Object[])args));

	result.append("\n<!-- F I X M E ---DEPRECATED"
		      +"\nUSE x.append(XMLHelper.tagged(...)) INSTEAD OF XMLHelper.addTagged(...,x)"
		      +"-->");

	return result.toString();
    }

    /**
       Puts the content in the tagname.

       If content is not present, creates a &lt;... /&gt; tag.

       This cannot handle attributes. The result is not threadsafe.

       XMLHelper.tagged("foo", "bar", "baz"); gives "&lt;foo&gt;bar baz&lt;/foo&gt;",
       XMLHelper.tagged("foo"); gives "&lt;foo /&gt;",
       XMLHelper.tagged("foo", XMLHelper.tagged("bar", "baz"));
           gives "&lt;foo&gt;&lt;bar&gt;baz&lt;/bar&gt;&lt;/foo&gt;".

       Note that several items in one tag will still be seperated by one single space.   

       2004DEC13 ums: would not actually generate &lt;... /&gt; tags.
       2004DEC13 ums: At request of mti, changed to not provide line breaking or sane spacing.
       
       2005JAN11 ums: can now take Objects, will call toString if needed.
     */
    public static StringBuilder tagged(String tagname, Object... content){
	StringBuilder result = new StringBuilder();

	if (content == null || content.length==0){

	    // yes, String+String is expensive, but these are short,
	    // so it shouldn't be quite so bad.

	    result.append("<"+tagname+" />");

	} else {
	    assert (content!=null);

	    result.append("<"+tagname+">");
	    for (Object s: content){

		// however, the content might be long, so we don't risk +.
		if (s!=null){ // let's not risk anything.

		    if (!(s instanceof CharSequence)){
			s = s.toString();
		    }

		    result.append((CharSequence)s);
		    result.append(' ');
		}
	    }
	    // FIXED 2005JAN03 ums: having null or empty members would
	    // result in > being chopped off. Whoopsie!
	    if (result.charAt(result.length()-1) == ' '){
		result.deleteCharAt(result.length()-1);
	    }
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
			System.out.println(e.toString());
			e.printStackTrace(out);
		}
	}
}
