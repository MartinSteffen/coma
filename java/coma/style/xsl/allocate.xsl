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
<xsl:apply-templates select="/result/allocation" />
</div> <!-- Main content end -->

</body>
</html>
</xsl:template>

<xsl:template match="/result/allocation">
<a href="#paper">Papers</a>
<table style="border:0" cellspacing="0" cellpadding="5">
	<tr>
		<td>Reviewer</td>
		<td></td>
		<td>Papers</td>
		<td></td>
		<td>Contentment (Prefs/Total)</td>
	</tr>
	<xsl:for-each select="/result/allocation/persons/reviewer">
	<tr>
		<td valign="top">
		<table style="border:0" cellspacing="0" cellpadding="2">
			<tr>
				<td class="normal" valign="top">
				<xsl:value-of select="@title"/><xsl:text> </xsl:text>
				<xsl:value-of select="@firstname"/><xsl:text> </xsl:text>
				<xsl:value-of select="@lastname"/>				
				</td>
			</tr>
			<tr>
				<td class="small" valign="top">[<font class="green">Pref. Papers</font>]<br/>
				<font class="green">
				<xsl:for-each select="./prefersPaper">
				<xsl:value-of select="/result/allocation/papers/paper[@id=current()/@id]/@title"/> <br/>
				</xsl:for-each>
				</font></td>
			</tr>
			<tr>
				<td class="small" valign="top">[<font class="blue">Pref. Topics</font>]<br/>
				<font class="blue">
				<xsl:for-each select="./prefersTopic">
				<xsl:value-of select="/result/topics/topic[@id=current()/@id]/@name"/><br/>
				</xsl:for-each>
				</font></td>
			</tr>
		</table>
		</td>
		<td width="10"></td>
		<td valign="top">
			<table style="border:0" cellspacing="0" cellpadding="2">
			<xsl:for-each select="./paper">
			<tr>
				<td valign="top"><table style="border:0" cellspacing="0" cellpadding="2">
				<tr>
					<td valign="top" class="normal">
					<xsl:if test="./@pref = 'paper'">
					<font class="green"><xsl:value-of select="/result/allocation/papers/paper[@id=current()/@id]/@title"/></font>
					</xsl:if>
					<xsl:if test="./@pref = 'topic'">
					<font class="blue"><xsl:value-of select="/result/allocation/papers/paper[@id=current()/@id]/@title"/></font>
					</xsl:if>
					<xsl:if test="./@pref = 'none'">
						<xsl:value-of select="/result/allocation/papers/paper[@id=current()/@id]/@title"/>
					</xsl:if>
					</td>
				</tr>
				<tr>
					<td valign="top" class="small">[
					<xsl:for-each select="/result/allocation/papers/paper[@id=current()/@id]/topic">
					<xsl:value-of select="/result/topics/topic[@id=current()/@id]/@name"/><xsl:text> </xsl:text>
					</xsl:for-each>]</td>
					</tr>
				</table></td>
			</tr>
			</xsl:for-each>	
			</table>
		</td>
		<td width="10"></td>
		<td valign="top" class="normal"><xsl:value-of select="round((./@contentment*100)div(/result/allocation/@papersPerReviewer))"/>% (<xsl:value-of select="./@contentment"/>/<xsl:value-of select="/result/allocation/@papersPerReviewer"/>)</td>
	</tr>
	</xsl:for-each>
	
</table><a name="paper"/>
<a href="#">Top</a>
<table style="border:0" cellspacing="0" cellpadding="5">
<tr>
	<td>Paper</td>
	<td width="10"></td>
	<td>Reviewer</td>
</tr>
<xsl:for-each select="/result/allocation/papers/paper">
<tr>
	<td class="normal" valign="top"><xsl:value-of select="./@title"/>
		<br/><font class="small">[# of Reviewer]<br/>
				<xsl:value-of select="count(./reviewer)"/>
		</font></td>
	<td width="10"></td>
	<td class="normal" valign="top">
	<table style="border:0" cellspacing="0" cellpadding="1">
	<xsl:for-each select="./reviewer">
	<tr>
		<td><xsl:value-of select="/result/allocation/persons/reviewer[@id=current()/@id]/@title"/><xsl:text> </xsl:text>
		<xsl:value-of select="/result/allocation/persons/reviewer[@id=current()/@id]/@firstname"/><xsl:text> </xsl:text>
		<xsl:value-of select="/result/allocation/persons/reviewer[@id=current()/@id]/@lastname"/></td>		
	</tr>
	</xsl:for-each>
	</table>
	</td>
</tr>
</xsl:for-each>
</table>
<a href="#">Top</a>
</xsl:template>

</xsl:stylesheet>