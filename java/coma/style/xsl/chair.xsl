<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" indent="yes"/>

<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" > 
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
<xsl:for-each select="/result/status">
<xsl:value-of select='.'/>
</xsl:for-each>
</div>


<!-- Site navigation menu -->
<div class="navbar">
<ul>
  <li><a href="index.html">Home page</a></li>
  <li>
  	<form action="invite" method="post">
  		<fieldset>
  			<input type="submit" value="invite person" class="submit-button" />
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