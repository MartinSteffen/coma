package coma.servlet.util;

import java.io.PrintWriter;

import javax.xml.transform.Source;
import javax.xml.transform.Templates;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.stream.StreamResult;


/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari</a>
 * @version 1.0
 */

public class XMLHelper {

	public String addWarning(String msg, StringBuffer result) {

		result.append("<warning>\n");
		result.append("<message>\n");
		result.append("<![CDATA["+msg+"]]>");
		result.append("</message>\n");
		result.append("</warning>\n");
		return result.toString();
	}

	public String addError(String msg, StringBuffer result) {

		result.append("<error>\n");
		result.append("<message>\n");
		result.append("<![CDATA["+msg+"]]>");
		result.append("</message>\n");
		result.append("</error>\n");
		return result.toString();
	}
	
	public String addInfo(String msg, StringBuffer result) {

			result.append("<info_eintrag>\n");
			result.append("<message>\n");
			result.append(msg);
			result.append("</message>\n");
			result.append("</info_eintrag>\n");
			return result.toString();
		}
	
	/**
	   * @param xmlSource die XML source
	   * @param xslSource die XSL source
	   * @param out ein PrintWriter auf den das Ergebnis geschrieben wird
	   * @author Hilmar Falckenberg
	   */
	public static void process(
		Source xmlSource,
		Source xslSource,
		PrintWriter out) {

		TransformerFactory tFactory;
		tFactory = TransformerFactory.newInstance();

		try {

			Templates templates = tFactory.newTemplates(xslSource);
			Transformer transformer = templates.newTransformer();
			transformer.transform(xmlSource, new StreamResult(out));
		} catch (Exception e) {
			e.printStackTrace(out);
		}
	}

}
