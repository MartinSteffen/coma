<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="xml" indent="yes"  doctype-public= "-//W3C//DTD XHTML 1.1//EN" 
    doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="iso-8859-1"/>

    <xsl:include href="navcolumn.xsl" />

    <xsl:template match="/">
      <html> 

      <head>
        <title>JCoMa:: a Conference Manager</title>
        <link rel="stylesheet" type="text/css" href="style/css/comastyle.css" />
      </head>

      <body>

        <!-- Site heading -->
        <div class="header">
          <h1 style="text-align:center">JCoMa</h1>
          <h3 style="text-align:center">Java Conference Manager</h3>
        </div><!-- Site heading -->


        <!-- Site navigation menu -->
        <xsl:call-template name="navcolumn" />

        <!-- Main content -->
        <div class="content">
          <xsl:apply-templates select="/subscribe/failed" />
          <xsl:apply-templates select="/subscribe/success" />
          <xsl:apply-templates select="/subscribe/form" />
          <xsl:apply-templates select="/subscribe/person" />
        </div> <!-- Main content -->
      </body>
    </html>
  </xsl:template>


  <xsl:template match="subscribe/failed">
    <h3>An Error has occurred,plaese check your data!</h3>
    <xsl:value-of select="." />
  </xsl:template>

  <xsl:template match="subscribe/success">
    <h3>
      <xsl:value-of select="."/>
    </h3>
    <h3>You are successfully added to your database!<br/> You are free to login!</h3>
  </xsl:template>

  <xsl:template match="/subscribe/form">
    <h3>Fill out the form to subscribe</h3>
    <form method="post" action="Subscribe" >
      <table style="border:0">
        <!-- added 2005JAN25 ums: select the conference -->
        <tr>
          <th>Conference:</th>
          <td>
            <select name="conference_id" size="1" style="border:red">
              <xsl:for-each select="conference">
                <option>
                  <xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute>
                  <xsl:value-of select="name" />
                </option>
              </xsl:for-each>
            </select>
          </td>
        </tr>
        <tr>
          <td>First name: </td>
          <td>
            <input class="input-box" type="text" name="first_name" size="20">
              <xsl:attribute name="value">
                <xsl:value-of select="first_name"/>
              </xsl:attribute>
            </input>
          </td>
        </tr>
        <tr>
          <td >Last name: </td>
          <td >
            <input class="input-box" style="border:1px solid red" type="text" name="last_name" size="20" >
              <xsl:attribute name="value">
                <xsl:value-of select="last_name"/>
              </xsl:attribute>
            </input>
          </td>
        </tr>
        <tr>
          <td >Title: </td>
          <td >
            <input class="input-box" type="text" name="title" size="20" >
              <xsl:attribute name="value">
                <xsl:value-of select="title"/>
              </xsl:attribute>
            </input>
          </td>
        </tr>
        <!--
             <tr>
               <td >Affiliation: </td>
               <td >
                 <input class="input-box" type="text" name="affiliation" size="20" >
                   <xsl:attribute name="value">
                     <xsl:value-of select="affiliation"/>
                   </xsl:attribute>
                 </input>
               </td>
             </tr>
             -->
             <tr>
               <td>Your email: </td>
               <td>
                 <input class="input-box" style="border:1px solid red" type="text" name="email" size="20" >
                   <xsl:attribute name="value">
                     <xsl:value-of select="email"/>
                   </xsl:attribute>
                 </input>
               </td>
             </tr>
             <!-- much commented out because we do that in userprefs now.
                  <tr>
                    <td >Phone number: </td>
                    <td >
                      <input class="input-box" type="text" name="phone_number" size="20" >
                        <xsl:attribute name="value">
                          <xsl:value-of select="phone_number"/>
                        </xsl:attribute>
                      </input>
                    </td>
                  </tr>
                  <tr>
                    <td >Fax number: </td>
                    <td >
                      <input class="input-box" type="text" name="fax_number" size="20" >
                        <xsl:attribute name="value">
                          <xsl:value-of select="fax_number"/>
                        </xsl:attribute>
                      </input>
                    </td>
                  </tr>
                  <tr>
                    <td >Street: </td>
                    <td >
                      <input class="input-box" type="text" name="street" size="20" >
                        <xsl:attribute name="value">
                          <xsl:value-of select="street"/>
                        </xsl:attribute>
                      </input>
                    </td>
                  </tr>
                  <tr>
                    <td >Postal code: </td>
                    <td >
                      <input class="input-box" type="text" name="postal_code" size="20" >
                        <xsl:attribute name="value">
                          <xsl:value-of select="postal_code"/>
                        </xsl:attribute>
                      </input>
                    </td>
                  </tr>
                  <tr>
                    <td >City: </td>
                    <td >
                      <input class="input-box" type="text" name="city" size="20" >
                        <xsl:attribute name="value">
                          <xsl:value-of select="city"/>
                        </xsl:attribute>
                      </input>
                    </td>
                  </tr>
                  <tr>
                    <td >State: </td>
                    <td >
                      <input class="input-box" type="text" name="state" size="20" >
                        <xsl:attribute name="value">
                          <xsl:value-of select="state"/>
                        </xsl:attribute>
                      </input>
                    </td>
                  </tr>-->
                  <tr>
                    <td >Country: </td>
                    <td >
                      <input class="input-box" type="text" name="country" size="20" >
                        <xsl:attribute name="value">
                          <xsl:value-of select="country"/>
                        </xsl:attribute>
                      </input>
                    </td>
                  </tr>
                  <tr>
                    <th>Author status:</th>
                    <td><input type="checkbox" name="willbeauthor" value="yes!" />
                      I might want to submit a paper to this conference.</td>
                  </tr>
                  <tr>
                    <td >Password: min 6 chars </td>
                    <td >
                      <input class="input-box" style="border:1px solid red" type="password" name="password" size="20" />
                    </td>
                  </tr>
                  <tr>
                    <td > Re-type password: </td>
                    <td >
                      <input class="input-box" style="border:1px solid red" type="password" name="repassword" size="20" />
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <input class="submit-button" type="submit" value="Submit Info" />
                    </td>
                  </tr>
                </table>
              </form>
              <div>Red fields are mandatory. If you plan to attend the
              conference or act as a
              reviewer, it is useful to go to User Data when logged in to enter
              additional data like mailable address and phone number.</div> 

            </xsl:template>

            <xsl:template match="subscribe/person">
              <form action="Login" method="post">
                <fieldset>
                  <label for="name">E-mail</label><br />
                  <input type="text" id="e-mail" class="input-box">
                    <xsl:attribute name="value">
                      <xsl:value-of select="email"/>
                    </xsl:attribute>
                  </input>	 
                  <br />
                  <i>(Your conference is remembered.)</i><br />
                  <label for="passwd">Password</label><br />
                  <input type="password" id="password" class="input-box" />
                  <br />
                  <input type="submit" value="login" class="submit-button" />
                  <input type="hidden" name="conference_id">
                    <xsl:attribute name="value"><xsl:value-of select="../confid" /></xsl:attribute>
                  </input>
                </fieldset>
              </form>
            </xsl:template>


          </xsl:stylesheet>