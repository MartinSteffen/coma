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
