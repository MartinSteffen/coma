<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="xml" indent="yes"  doctype-public= "-//W3C//DTD XHTML 1.1//EN" 
    doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="iso-8859-1" />

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
        </div>

        <div class="status-line">
          login not correct
        </div>

        <!-- Site navigation menu -->
        <xsl:call-template name="navcolumn" />
        <!--
        <div class="navbar">
          <ul>
            <li><a href="index.html">Home page</a></li>
            <li>
              <form action="Login" method="post">
                <fieldset>
                  <label for="name">Name</label><br />
                  <input type="text" name= "userID" id="user-name" class="input-box" />
                    <br />
                    <label for="passwd">Password</label><br />
                    <input type="password" name ="passwd" id="passwd" class="input-box" />
                      <br />
                      <a href="index.html">Forgot your Password?</a>
                      <br />
                      <input type="submit" value="login" class="submit-button" />
                    </fieldset>
                  </form>
                </li>
                
                <li><a href="http://snert.informatik.uni-kiel.de:8888/coma/">tomcat directory</a></li>
                <li><a href="http://snert.informatik.uni-kiel.de:8080/svn/coma/">svn repository</a></li>
                <li><a href="http://snert.informatik.uni-kiel.de:8080/~wprguest3/phpmyadmin/">phpMyAdmin</a></li>
                <li>
                  <a href="http://validator.w3.org/check?uri=referer">
                    <img src="./img/valid-xhtml11.png" alt="Valid XHTML 1.1!" style="border:0;width:68px;height:20px"  />
                  </a>
                  <a href="http://jigsaw.w3.org/css-validator/check/referer">
                    <img style="border:0;width:68px;height:20px" src="./img/vcss.png" alt="Valid CSS!" />
                  </a>
                </li>
              </ul> 

              

            </div>
            -->

            <!-- Main content -->
            <div class="content">

              <xsl:for-each select="/result/content">
                <xsl:value-of select='.'/><br></br>
              </xsl:for-each>


            </div> <!-- Main content end -->

          </body>
        </html>
      </xsl:template>
    </xsl:stylesheet>
