     <!-- Stylesheet for servlet/servlets/ShowReports. author:ums  -->
     <!-- No, not much functionality yet. (Well, there's not much in ShowReports either) -->
     <!-- see NO PAGE YET for demo of showreports. -->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="xml" 
    indent="yes" 
    doctype-public="-//W3C//DTD XHTML 1.1//EN"
    doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"
    encoding="iso-8859-1"
    />

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
        <!-- more-or-less verbatim from subscribe.xsl -->
        <!-- Site navigation menu -->
        <div class="navbar">
          <ul>
            <li><a href="index.html">Home page</a></li>
            <li><a href="http://snert.informatik.uni-kiel.de:8888/coma/">tomcat directory</a></li>
            <li><a href="http://snert.informatik.uni-kiel.de:8080/svn/coma/">svn repository</a></li>
            <li>
              <a href="http://validator.w3.org/check?uri=referer">
                <img src="./img/valid-xhtml11.png" alt="Valid XHTML 1.1?" style="border:0;width:68px;height:20px"  />
              </a>
              <a href="http://jigsaw.w3.org/css-validator/check/referer">
                <img style="border:0;width:68px;height:20px" src="./img/vcss.png" alt="Valid CSS?" />
              </a>
            </li>
          </ul> 
        </div><!-- Site navigation menu -->

        <xsl:apply-templates select="/content" />
      </body>
    </html>
  </xsl:template>

  <xsl:template match="/result">
    <div class="header"><h1><xsl:value-of select="pagetitle" /></h1></div>
    <div class="content">
      <xsl:apply-templates select="noSession" />
      <xsl:apply-templates select="databaseError" />
      <xsl:apply-templates select="unauthorized" />

      <xsl:if test="allReportsIntro">
        These are all the reports visible to you at this time.
        <xsl:apply-templates select="info" />
      </xsl:if>
    </div>
  </xsl:template>

  <xsl:template match="info">
      <table>
        <tr><th>ID</th><th>on paper...</th></tr>
        <xsl:foreach select="ReviewReport">
          <tr>
            <td><xsl:value-of select="id" /></td>
            <td><xsl:value-of select="paper/title" /></td>
          </tr>
        </xsl:foreach>
      </table>
  </xsl:template>

  <xsl:template match="noSession">
    <div>
    You have no session open. Maybe you forgot to log in, or your
    browser does not support cookies.
    </div>
  </xsl:template>

  <xsl:template match="databaseError">
    <div>
      Something went wrong with the database backend. Please tell a
      wizard to look at the logs.
    </div>
  </xsl:template>

  <xsl:template match="unauthorized">
    <div>
      You're not currently allowed to know that. I suspect you are not
      logged in.
    </div>
  </xsl:template>

</xsl:stylesheet>