package coma.servlet.util;


import javax.servlet.http.HttpServletRequest;


/**
   A minimalistic helper class to keep track of the state of a
   servlet. We can store one single character, it will appear as a tag
   "servletState". The XSLT for the servlet must call the following rule:

   <pre>
   &lt;xsl:template match="//servletState"&gt;
   &lt;input type="hidden" name="servletState" value="{@state}"&gt;
   &lt;/xsl:template&gt;
   </pre>

   (using <pre>&lt;xsl:apply-templates select="//servletState" &gt;</pre>)

   Embed the state by writing toString()'s result to the reply. Make
   sure it will end up within a form.

   @author ums
 */
public class PageStateHelper {

    public static final char NULLSTATE='_'; //was 2205 EMPTY SET, blerch, let's take something safer.

    Character state = null;

    /**
       Constructor.

       We need the servlet's HttpServletRequest to ask it for the
       state parameter.
     */
    public PageStateHelper(HttpServletRequest hsr){
	try {
	    state = hsr.getParameter("servletState").charAt(0);
	} catch (Exception exc){ 
	    // oh, that didn't work. just assume that's because we
	    // don't have a state yet.
	    state = NULLSTATE;
	}
    }

    public char get(){return state;} //whee! autounboxing :D
    public void set(char c){state = c;}

    public String toString(){
	return "<servletState state=\""+state+"\" />";
    }

}
