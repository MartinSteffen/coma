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



</div> <!-- Main content end -->

</body>
</html>
</xsl:template>

<xsl:template match="content">
<h3>Welcome to JCoMa!</h3>

<p>It lacks images, but at least it has style.
And it has links, even if they don't go
anywhere ...</p>
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
<h3><xsl:value-of select="//person//last_name"/> ,you are loged in for <xsl:value-of select="//conference//name"/></h3>
<p>The Conference is about: <xsl:value-of select="//conference//description"/></p>
<p>For more detailed information please visit our <a><xsl:attribute name="href"><xsl:value-of select="//conference//homepage" /></xsl:attribute>Conference Homepage</a></p>
<p>The conference will the place from: <xsl:value-of select="//conference//start"/> to <xsl:value-of select="//conference//end"/></p>
<p>Papers can sumbitted until: <xsl:value-of select="//conference//paper"/></p>
<p></p>
<xsl:value-of select="."/>
</xsl:template>
</xsl:stylesheet>