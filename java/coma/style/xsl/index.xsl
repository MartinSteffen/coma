<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:output method="xml" indent="yes"  doctype-public= "-//W3C//DTD XHTML 1.1//EN" 
doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="iso-8859-1"/>
<xsl:include href="navcolumn.xsl" />
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

<!-- Site navigation menu -->
<xsl:call-template name="navcolumn" /> 

<!-- Main content -->
<div class="content">
<xsl:apply-templates select="/login/password_error" />
<xsl:apply-templates select="/login/form_error" />
<xsl:apply-templates select="/login/success" />
<xsl:apply-templates select="/content" />
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
<xsl:template match="content">
<h3>data</h3>
<xsl:value-of select="/"/>
</xsl:template>
<xsl:template match="login/password_error">
<h3>An Error has occurred,plaese check your password and email!
<xsl:value-of select="/"/>
</h3>
</xsl:template>
<xsl:template match="login/form_error">
<h3>An Error has occurred,plaese check your data!</h3>
</xsl:template>
<xsl:template match="login/success">
<h3>You are loged in</h3>
</xsl:template>
</xsl:stylesheet>