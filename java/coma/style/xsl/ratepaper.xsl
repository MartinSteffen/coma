     <!-- Stylesheet for servlet/servlets/RatePaper. author:ums  -->
     <!-- see http://snert:8080/~wprguest3/editreport_demo.html for demo of editreport. -->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="xml" 
    indent="yes" 
    doctype-public="-//W3C//DTD XHTML 1.1//EN"
    doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"
    encoding="iso-8859-1"
    />

  <xsl:include href="navcolumn.xsl" />
  <xsl:include href="stderrors.xsl" />

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
        <xsl:call-template name="navcolumn" />
        <xsl:apply-templates select="/content" />
      </body>
    </html>
  </xsl:template>

  <xsl:template match="/content">
    <div class="header"><h1><xsl:value-of select="pagetitle" /></h1></div>
    <div class="content">
      <form action="RatePaper" method="post">
        <xsl:apply-templates select="//servletState" />
        <xsl:call-template name="stderrors" />

        <xsl:choose>

          <xsl:when test="papersToRate">
            <div>
              These are the papers you are allowed to rate. Please select one and
              click "Rate selected paper!" to continue.
            </div>
            <div>
              <table>
                <tr>
                  <th><!-- radio button --></th>
                  <th>Title</th>
                  <th>Author</th>
                  <th>last change</th>
                </tr>
                <xsl:for-each select="selectpaper/paper">
                  <tr>
                    <td>
                      <input type="radio" name="paperid">
                        <xsl:attribute name="value">
                          <xsl:value-of select="./id" />
                        </xsl:attribute>
                      </input>
                    </td>
                    <td class="papertitle"><xsl:value-of select="./title" /></td>
                    <td class="authorname"><xsl:value-of select="./Person/last_name" /></td>
                    <td class="revdate"><xsl:value-of select="./last_edited" /></td>
                  </tr>
                </xsl:for-each>
              </table>
            </div>
            <xsl:for-each select="submit">
              <div>
                <button name="dominate" type="submit" class="submit-button">
                  Rate selected paper!
                </button>
              </div>
            </xsl:for-each>
          </xsl:when>


          <xsl:when test="editReport">
            <xsl:apply-templates select="editReport" />
            <xsl:for-each select="submit">
              <div>
                <button name="dominate" type="submit" class="submit-button">
                  Submit Changes
                </button>
              </div>
            </xsl:for-each>
          </xsl:when>

          <xsl:when test="tooLate">
            <div>
              I am very sorry to inform you that the deadline for this
              has already passed.
            </div>
          </xsl:when>

          <xsl:when test="updateInProgress">
            <div>
              Update complete.
            </div>
          </xsl:when>
          
          <xsl:otherwise>
            <div>
              <font size="+3"  color="#ff0000">
                You should never see this!
                I am in a state I do not know!
              </font>
            </div>
          </xsl:otherwise>
        </xsl:choose>
        
      </form>
    </div> <!-- class="content" -->
  </xsl:template>

  <xsl:template match="editReport/ReviewReport">
    <div>
      You can now edit your report. When you are done, click
      "Submit Changes" to continue.
    </div>
    <!-- fixme: make proper css. -->
    <div class="report" style="background-color: #f0f0e0">
      <div class="reportheading">
        Reviewer's Report
      </div>
      <div>
        <table rules="rows" border="1">
          <tr>
            <th valign="top">by reviewer</th>
            <td>
              <xsl:value-of select="Person/last_name" />, 
              <xsl:value-of select="Person/email" />
            </td>
          </tr>
          <tr>
            <th valign="top">on the paper titled</th>
            <td>
              <a>
                <xsl:attribute name="href">
                  <xsl:value-of select="paper/filename" />
                </xsl:attribute>
                <xsl:value-of select="paper/title" />
              </a>, 
              revision <xsl:value-of select="paper/version" />
              of <xsl:value-of select="paper/last_edited" />
            </td>
          </tr>
          <tr>
            <th  valign="top">Summary:</th>
            <td>
              <textarea name="summary" cols="72" rows="6"><xsl:value-of select="summary" />&#160;</textarea>
            </td>
          </tr>
          <xsl:for-each select="rating">
            <tr>
              <th valign="top"><xsl:value-of select="criterion/name" />:</th>
              <td>
                <xsl:value-of select="criterion/description" />
                <p>You can rate this criterion from 1 (worst) to <xsl:value-of select="criterion/maxValue" /> (best).
                It has Quality Rating Factor <xsl:value-of
                select="criterion/qualityRating" />. (Higher QRF
                means bigger influence on total result.)
              </p>
              <p>
                <table>
                  <tr>
                    <th valign="top">Grade:</th>
                    <td>
                      <!--This is "fun": manually build choices. -->
                      <select size="1">
                        <xsl:attribute name="name">ratingForCriterion<xsl:value-of select="criterion/id" />Grade</xsl:attribute>
                        <xsl:call-template name="options-one-to">
                          <xsl:with-param name="maxValue" select="criterion/maxValue" />
                          <xsl:with-param name="grade" select="grade" />
                        </xsl:call-template>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th valign="top">Remarks:</th>
                    <td>
                      <textarea cols="60" rows="4"><xsl:attribute name="name">ratingForCriterion<xsl:value-of select="criterion/id" />Commt</xsl:attribute><xsl:value-of select="comment" />&#160;</textarea>
                    </td>
                  </tr>
                </table>
              </p>
            </td>
          </tr>
        </xsl:for-each>
        <tr>
          <th valign="top">Remarks:</th>
          <td>
            <textarea name="remarks" cols="72" rows="12"><xsl:value-of select="remarks" />&#160;</textarea>
          </td>
        </tr>
        <tr>
          <th valign="top">Confidental Remarks</th>
          <td>
            <i>Note: your prior entry is not displayed.</i>
            <textarea name="confidental" cols="72" rows="6">&#160;</textarea>
          </td>
        </tr>
      </table>
    </div>
  </div>
</xsl:template>

<!-- Modelled after Jiri Jirat's suggestion in           -->
<!-- http://lists.zvon.org/l/showmsg.xp?ln=zvon&mid=1242 -->
<!--                                                     -->
<!-- I knew programming WHILE_0 would be handy one day   -->
<xsl:template name="options-one-to">
  <xsl:param name="i">1</xsl:param> <!-- start from 1 -->
  <xsl:param name="grade">666</xsl:param>
  <xsl:param name="maxValue">2</xsl:param>

  <option>
    <xsl:if test="$grade = $i">
      <xsl:attribute name="selected">selected</xsl:attribute>
    </xsl:if>
    <xsl:value-of select="$i" />
  </option>
  <xsl:if test="$i &lt; $maxValue">
    <xsl:call-template name="options-one-to">
     <xsl:with-param name="i" select="$i + 1"/>
     <xsl:with-param name="maxValue" select="$maxValue" />
     <xsl:with-param name="grade" select="$grade" />
   </xsl:call-template>
  </xsl:if>
</xsl:template>

</xsl:stylesheet>
