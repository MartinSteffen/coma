<!-- 
     handle some of the standard errors defined in UserMessage.java

     to use, include this file with <xsl:include href="stderrors.xsl" /> and call
     <xsl:call-template name="stderrors" /> where the result will end
     up inside the body.
     -->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="xml" 
    indent="yes" 
    doctype-public="-//W3C//DTD XHTML 1.1//EN"
    doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"
    encoding="iso-8859-1"
    />

  <xsl:template name="stderrors">
    <xsl:if test="//noSession">
      No session data could be found. Maybe you are not logged in, or
      your browser does not accept cookies when it should.
    </xsl:if>
    <xsl:if test="//databaseError">
      A general database error has occured, or some input data was
      faulty. (Yes, this is not very helpful.)
    </xsl:if>
    <xsl:if test="//unauthorized">
      You are not allowed to know that. Maybe you forgot to log in, or
      some cookie has mysteriously vanished.
    </xsl:if>
    <xsl:if test="//unknownXMLmode">
      An internal error has occured while transforming DB entries to
      XML.
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>

