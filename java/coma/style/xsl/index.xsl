<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:output method="xml" indent="yes"  doctype-public= "-//W3C//DTD XHTML 1.1//EN" 
doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="iso-8859-1"/>

<xsl:template match="/">
<html > 
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
<h3 style="text-align:center">You are not loged in!</h3>
</div>

<!-- Site navigation menu -->
<div class="navbar">
<ul>
  <li><a href="index.html">Home page</a></li>
  <li>
  	<form action="#" method="post">
  	<fieldset>
				<label for="name">Name</label><br />
				<input type="text" id="name" class="input-box" />
				<br />
				<label for="passwd">Password</label><br />
				<input type="password" id="passwd" class="input-box" />
				<br />
				<a href="index.html">Forgot your Password?</a>
				<br />
				<input type="submit" value="login" class="submit-button" />
		</fieldset>
		</form>
	</li>
	<li>
	<form action="Subscribe" method="post">	
	<fieldset>
		<label for="subscribe">not jet a Login? hurry up</label><br />
		<input type="submit" id="subscribe" value="subscribe!" class="submit-button" />
		</fieldset>
  	</form>
  </li>
  <li><a href="http://snert.informatik.uni-kiel.de:8888/coma/">tomcat directory</a></li>
  <li><a href="http://snert.informatik.uni-kiel.de:8080/svn/coma/">svn repository</a></li>
  <li><a href="http://snert.informatik.uni-kiel.de:8080/~wprguest3/phpmyadmin/">phpMyAdmin</a></li>
  <li><a href="http://snert.informatik.uni-kiel.de:8080/~wprguest3/downloads/">downloads</a></li>
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

<p>Welcome to JCoMa!</p>

<p>It lacks images, but at least it has style.
And it has links, even if they don't go
anywhere ...</p>

<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>
<p>There should be more here, but I don't know what yet.</p>

<!-- Sign and date the page, it's only polite! -->
<address>Made  30 November 2004<br/>
  by mti.
</address>
</div> <!-- Main content end -->

</body>
</html>
</xsl:template>
</xsl:stylesheet>