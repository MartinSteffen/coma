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
<xsl:apply-templates select="/content/conference_end" />
<xsl:apply-templates select="/content/show_program" />

</div> <!-- Main content end -->

</body>
</html>
</xsl:template>


<xsl:template match="/content/conference_end">

<xsl:for-each select="/content/conference_end">
		<a>
			<xsl:attribute name = "href">index.html?action=ShowProg&amp;name=<xsl:value-of select ="."/>
			</xsl:attribute><xsl:value-of select ="."/>
		</a>
		</xsl:for-each>
</xsl:template>

<xsl:template match="/content/show_program">


<table class="chair" cellpadding="5">
<xsl:for-each select="content/day">
	<tr align="center">
		<td colspan="3">
		DAY
			<xsl:value-of select="@date"/>
			:
			<xsl:value-of select="date"/>
		</td>
	</tr>
	<tr align="center">
		<th>Time</th>
		<th>Title</th>
		<th>Author</th>
	</tr>
	<xsl:for-each select="FINISH">
	<tr>
		<td>
			<xsl:value-of select="time"/>
		</td>
		<td>
			<xsl:value-of select="title"/>
		</td>
		<td>
			<xsl:value-of select="author"/>
		</td>
	</tr>
	</xsl:for-each>
</xsl:for-each>
</table>

</xsl:template>



<xsl:template match="content">

<h3>Welcome to JCoMa!</h3>


</xsl:template>

<xsl:template match="login/password_error">
<h3>An Error has occurred, please check your password and email!
<xsl:value-of select="/"/>
</h3>
</xsl:template>
<xsl:template match="login/form_error">
<h3>An Error has occurred, please check your data!</h3>
</xsl:template>
<xsl:template match="login/success">
<h3><xsl:value-of select="//person//last_name"/> ,you are logged in for <xsl:value-of select="//conference//name"/></h3>
<p>The Conference is about: <xsl:value-of select="//conference//description"/></p>
<p>For more detailed information please visit our <a><xsl:attribute name="href"><xsl:value-of select="//conference//homepage" /></xsl:attribute>Conference Homepage</a></p>
<p>The conference will the place from: <xsl:value-of select="//conference//start"/> to <xsl:value-of select="//conference//end"/></p>
<p>Papers can submitted until: <xsl:value-of select="//conference//paper"/></p>
<p></p>
<xsl:value-of select="."/>
</xsl:template>
</xsl:stylesheet>