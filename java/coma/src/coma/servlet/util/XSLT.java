package coma.servlet.util;

import javax.servlet.http.HttpServlet;
import javax.xml.transform.stream.StreamSource;

/**
   Trivial wrapper class that saves you from assembling file paths
   manually.
 */
public class XSLT {

    /** pass only the naked filename, like "userprefs". Path is added
     * automatically, as is extension ".xsl"
     */
    public static StreamSource file(HttpServlet that, String fname){
	return new StreamSource(that.getServletContext().getRealPath("")+"/style/xsl/"+fname+".xsl");
    }

}
