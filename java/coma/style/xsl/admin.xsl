<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" indent="yes"  doctype-public= "-//W3C//DTD XHTML 1.1//EN" 
doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="iso-8859-1"/>
<xsl:include href="navcolumn.xsl"/>
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


<!-- Site navigation menu -->
<xsl:call-template name="navcolumn"/>

<!-- Main content -->
<div class="content">
<xsl:apply-templates select = "/result/setup/content"/>
<xsl:apply-templates select = "/result/setup_complete/content"/>
</div>
</body>
</html>
</xsl:template>

<xsl:template match="/result/setup/content">
to insert a conference please fill out the chair data

<form action="Admin?action=add_conference" method="post">
<table class="chair">
<tr>
	<td>
		first name:
	</td>
	<td>
		<input type="text" size="30" name="first_name" id="first_name" maxlength="30">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/setup/content/first_name"/>
			</xsl:attribute></input>
	</td>
</tr>
<tr>
	<td>
		last name:
	</td>
	<td>
		<input type="text" size="30" name="last_name" id="last_name" maxlength="30">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/setup/content/last_name"/>
			</xsl:attribute></input>
	</td>
</tr>
<tr>	
	<td>
		email:
	</td>
	<td>
		<input type="text" name="email" id="email" size="30" maxlength="30">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/setup/content/email"/>
			</xsl:attribute></input>
	</td>
</tr>
<tr>
	<td>
		password:
	</td>
	<td>
		<input type="passwd" size="30" name="passwd" id="passwd" maxlength="30">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/setup/content/passwd"/>
			</xsl:attribute></input>
	</td>
</tr>	
</table>
<input type="submit" value="Send"/>
</form>
</xsl:template>

<xsl:template match="/result/setup_complete/content">
<xsl:apply-templates select = "/result/setup_complete/status"/><br/>
Thank You!
</xsl:template>

</xsl:stylesheet>



