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
           <li><a href="Subscribe">Not registered?</a></li>
      </xsl:when>

      <!-- any proper content based on the user's role. -->
      <xsl:otherwise>
        Hello, <xsl:value-of select="//navcolumn//Person/first_name" />&#160;
      <xsl:value-of select="//navcolumn//Person/last_name" />!
        <li>User
        <ul>
        	<!-- logout button -->
          <li><a href="Login?action=logout">Logout!</a></li>
          <li><a href="UserPrefs">Edit User Data</a></li>
        </ul>
        </li>
        <xsl:if test="//navcolumn//isChair">
			<li>Chair<xsl:if test="//init">
						(initial setup)</xsl:if>
          		<ul>
          		 
          		 <xsl:choose>
          		 <xsl:when test="//init">
          		 <li><a href="Chair?action=setup&amp;target=topics">topic(s) setup</a></li>
          		 <li><a href="Chair?action=setup&amp;target=criteria">rating(s) setup</a></li>
          		 <li><a href="Chair?action=setup&amp;target=save">SAVE</a></li> 
          		 </xsl:when>
          		 <xsl:otherwise>
          		 	<li><a href="Chair?action=setup&amp;target=conference">conference setup</a></li>
          		 	<li><a href="Chair?action=show_topics">list of topics</a></li> 
					 <li><a href="Chair?action=invite_person">invite person</a></li> 
            	 	<li><a href="Chair?action=show_authors">list of authors</a></li> 
 					 <li><a href="Chair?action=show_reviewers">list of reviewers</a></li>
 					 <li><a href="Chair?action=show_papers">list of papers</a></li>
 					 <li><a href="Chair?action=email">email</a></li>
 					 <li><a href="Chair?action=program">create Program</a></li>
          		 </xsl:otherwise>
          		 </xsl:choose>
		          
 			 	</ul>
          </li>
        </xsl:if>
        <xsl:if test="//navcolumn//isAuthor">
			<li>Author
          		<ul>
					<li><a href="Author">Overview</a></li>
		            <li><a href="Author?action=submitpaper">submit new paper</a></li>
		        </ul>
		    </li>
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
<li>Best viewed with any browser, except ie</li>
</ul> 
</div><!-- Site navigation menu -->
<xsl:comment>End of Navbar</xsl:comment>

</xsl:template>

</xsl:stylesheet>

