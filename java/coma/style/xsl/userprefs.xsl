<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <!-- Stylesheet for servlet/servlets/UserPrefs. -->
  <!-- first, very incomplete draft by ums. -->

  
  <xsl:output method="xml" 
    indent="yes" 
    doctype-public="-//W3C//DTD XHTML 1.1//EN"
    doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"
    encoding="iso-8859-1"
    />
  
  <xsl:include href="navcolumn.xsl" />
  
  <xsl:template match="//servletState">
    <input type="hidden" name="servletState" value="{@state}"></input>
  </xsl:template>

  <xsl:template match="/">
    <html>
      <head>
        <link rel="stylesheet" type="text/css" href="style/css/comastyle.css" />
        <title>
          JCoMa: 
          <xsl:value-of select="/content/pagetitle" />
        </title>
        <xsl:value-of select="meta" />
      </head>
      <body>
        <div class="content">
        <xsl:call-template name="navcolumn" />
        <form action="UserPrefs" method="post">
          <xsl:apply-templates select="//servletState" />
          <xsl:apply-templates select="//editable" />
          <xsl:apply-templates select="//noneditable" />
        </form>
      </div>
      </body>
    </html>
  </xsl:template>
  
  <xsl:template match="editable">
    
    You can now change the information JComa has stored about you in
    the fields below. Press "save changes" when you're done.

    <div><h3>Preferred Topics</h3>
    <p>Please select your areas of expertise from the list below. This
    will help the committee to give you papers that are interesting to
    you, should you become or be a reviewer.</p>
    <select name="preferredtopics" size="5" multiple="multiple">
      <xsl:for-each select="topics/topic">
        <option>
          <xsl:attribute name="value">
            <xsl:value-of select="id" />
          </xsl:attribute>
          <xsl:value-of select="name" />
        </option>
      </xsl:for-each>
    </select>
    <span class="fasthelp">
      You can select multiple entries by holding the Control key.
    </span>
  </div>
    <div>
      <button name="dominate" type="submit" class="submit-button">
        Save changes
      </button>
    </div>
  </xsl:template>

  <xsl:template match="noneditable">
    FIXME: do some default display
  </xsl:template>

</xsl:stylesheet>
