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
       Puts the content in the tagname.

       If content is not present, creates a <... /> tag.
       This cannot handle attributes. The result is not threadsafe.
     */
    public static StringBuilder tagged(String tagname, CharSequence... content){
	StringBuilder result = new StringBuilder();
	if (content == null){
	    // yes, String+String is expensive, but these are short,
	    // so it shouldn't be quite so bad.
	    result.append("<"+tagname+" />");
	} else {
	    result.append("<"+tagname+">");
	    for (CharSequence s: content){
		// however, the content might be long, so we don't risk +.
		if (s!=null){ // let's not risk anything.
		    result.append("\n  ");
		    result.append(s);
		}
	    }
	    result.append("\n</"+tagname+">");
	}
	result.append('\n');
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
