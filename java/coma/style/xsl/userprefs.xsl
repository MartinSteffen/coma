<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <!-- Stylesheet for servlet/servlets/UserPrefs. -->
  
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

  <xsl:template match="//error">
    <xsl:value-of select="." />
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
        <div class="content">
          <xsl:call-template name="stderrors" />
          <xsl:apply-templates select="//error" />
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

    <input type="hidden" name="id">
      <xsl:attribute name="value">
        <xsl:value-of select="person/id" />
      </xsl:attribute>
    </input>
    <div><h3>Contact Data</h3>
    <table style="border:0">
      <tr>
        <td>First name: </td>
        <td>
          <input class="input-box" type="text" name="first_name" size="20">
            <xsl:attribute name="value">
              <xsl:value-of select="person/first_name"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td >Last name: </td>
        <td >
          <input class="input-box" style="border:1px solid red" 
            type="text" name="last_name" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/last_name"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td >Title: </td>
        <td >
          <input class="input-box" 
            type="text" name="title" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/title"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td >Affiliation: </td>
        <td >
          <input class="input-box" 
            type="text" name="affiliation" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/affiliation"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td>Your email: </td>
        <td>
          <input class="input-box" style="border:1px solid red" 
            type="text" name="email" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/email"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td >Phone number: </td>
        <td >
          <input class="input-box" 
            type="text" name="phone_number" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/phone_number"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td >Fax number: </td>
        <td >
          <input class="input-box" 
            type="text" name="fax_number" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/fax_number"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td >Street: </td>
        <td >
          <input class="input-box" 
            type="text" name="street" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/street"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td >Postal code: </td>
        <td >
          <input class="input-box" 
            type="text" name="postal_code" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/postal_code"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td >City: </td>
        <td >
          <input class="input-box" 
            type="text" name="city" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/city"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td >State: </td>
        <td >
          <input class="input-box" 
            type="text" name="state" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/state"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
      <tr>
        <td >Country: </td>
        <td >
          <input class="input-box" 
            type="text" name="country" size="20" >
            <xsl:attribute name="value">
              <xsl:value-of select="person/country"/>
            </xsl:attribute>
          </input>
        </td>
      </tr>
    </table>
  </div>

  <xsl:if test="topics/topic">
    
    <div>
      <h3>Preferred Topics</h3>
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
  </xsl:if>

  <div>
    Enter your password:
    <input class="input-box" style="border:1px solid red" type="password" name="password" size="20" />,
    enter your password again to confirm:
    <input class="input-box" style="border:1px solid red" type="password" name="repassword" size="20" />
    then press
    <input value="Save" type="submit" class="submit-button" />
  </div>
</xsl:template>

<xsl:template match="noneditable">
  Thank you. The following data has been saved:
  <table style="border:0">
    <tr>
      <th>Name:</th>
      <td>
        <xsl:value-of select="./person/first_name" />&#160;
        <xsl:value-of select="./person/last_name" />, 
        <xsl:value-of select="./person/title" />
      </td>
    </tr>
    <tr>
      <th>with</th><td><xsl:value-of select="person/affiliation" />,</td>
    </tr>
    <tr>
      <th>eMail:</th><td><xsl:value-of select="person/email" /></td>
    </tr><tr>
      <th>Phone:</th><td><xsl:value-of select="person/phone_number" /></td>
    </tr><tr>
      <th>Fax:  </th><td><xsl:value-of select="person/fax_number" /></td>
    </tr><tr>
      <th>Postal Address:</th>
      <td>
        <xsl:value-of select="person/street" /><br/>
        <xsl:value-of select="person/city" /> &#160;
        <xsl:value-of select="person/postal_code" /><br />
        <!-- person/state: drunk :-D -->
        <xsl:value-of select="person/state" /><br />
        <xsl:value-of select="person/country" />
      </td>
    </tr>
  </table>
</xsl:template>

</xsl:stylesheet>
