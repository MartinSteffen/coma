<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="xml" 
    indent="yes" 
    doctype-public="-//W3C//DTD XHTML 1.1//EN"
    doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"
    encoding="iso-8859-1"
    />

  <xsl:template match="//navcolumn" name="navcolumn">
    <!-- Site navigation menu -->
    <xsl:comment>Navbar</xsl:comment>
    <xsl:if test="//navcolumn//theTime">
      <div class="date">JComa Time:
      <xsl:value-of select="//navcolumn//theTime" />
    </div>
  </xsl:if>
  
  
  <div class="navbar">
    <ul>
      <xsl:choose>

        <!-- Login/Subscribe. This is mut.ex. with
             any other meaningful content of the navbar. -->
        <xsl:when test="//navcolumn//noUser"> <!-- Login/Subscribe -->
        <li>
          <form action="Login" method="post">
            <fieldset>
              <div>
                <div>
                  <select name="conference_id">
                    <xsl:for-each select="//conference_list//conference">
                      <option>
                        <xsl:attribute name="value">
                          <xsl:value-of select="id"/>
                        </xsl:attribute>
                        <xsl:value-of select="name"/>
                      </option>
                    </xsl:for-each>
                  </select>
                </div>
                <div>
                  <label for="email">E-Mail</label>
                  <input type="text" name="email" class="input-box" />
                </div>
                <div>
                  <label for="password">Password</label>
                  <input type="password" name="password" class="input-box" />
                </div>
                <div>
                  <input type="submit" value="login" class="submit-button" />
                </div>
                <div>
                  <a href="index.html">Forgot your Password?</a></div>
                </div>
              </fieldset>
            </form>
          </li>
          <li>
            <form action="Subscribe" method="post">	
            <fieldset>
              <label for="subscribe">Not registered?</label><br />
              <input type="submit" id="subscribe" value="subscribe!" class="submit-button" />
            </fieldset>
          </form>
        </li>
      </xsl:when>

      <!-- any proper content based on the user's role. -->
      <xsl:otherwise>
        <li>User
        <ul>
        	<!-- logout button -->
          <li>
          	<form action="Login" method="post">	
          	<input type="hidden" name="action" value="logout"/>
            <input type="submit" name="logout" value="Logout!" class="submit-button" />
          </form>
          </li>
          <li><a href="UserPrefs">Edit User Data</a></li>
        </ul>
        </li>
        <xsl:if test="//navcolumn//isChair">
          <li>Chair
          <ul>
            <li>
              <a href="Chair?action=setup">Setup</a></li>
              <li><a href="Chair?action=invite_person">invite person</a></li>
            </ul>
          </li>
        </xsl:if>

        <xsl:if test="//navcolumn//isAuthor">
          <li>Author
          <ul>
            <li>View Status</li>
            <li><form action="Author?action=submitpaper" method="post">
            <input type="submit" value="submit paper" class="submit-button" />
          </form></li>
          <li>Update</li>
          <li>Withdraw</li>
        </ul></li>
      </xsl:if>

      <xsl:if test="//navcolumn//isReviewer">
        <li>Reviewer<ul>
        <li><a href="EditReport">Write Review</a></li>
        <li><a href="ShowReports">Show Reports</a></li>
      </ul></li>
    </xsl:if>

  </xsl:otherwise>

</xsl:choose>

<li><a href="index.html">Home page</a></li>
<li><a href="http://snert.informatik.uni-kiel.de:8888/coma/">tomcat directory</a></li>
<li><a href="http://snert.informatik.uni-kiel.de:8080/svn/coma/">svn repository</a></li>
<li>
  <a href="http://validator.w3.org/check?uri=referer">
    Valid XHTML 1.1?
  </a>
  <a href="http://jigsaw.w3.org/css-validator/check/referer">
    Valid CSS?
  </a>
</li>

</ul> 
</div><!-- Site navigation menu -->
<xsl:comment>End of Navbar</xsl:comment>

</xsl:template>

</xsl:stylesheet>

