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
<xsl:apply-templates select="/author/failed" />
<xsl:apply-templates select="/author/error" />
<xsl:apply-templates select="/author/success" />
<xsl:apply-templates select="/author/submitpaper"/>
<xsl:apply-templates select="/author/writefile" />
<xsl:apply-templates select="/author/showpaper/success" />
<xsl:apply-templates select="/author/showpaper/failed" />
<xsl:apply-templates select="/author/showdetails"/>

</div> <!-- Main content end -->

</body>
</html>
</xsl:template>
<xsl:template match="author/showdetails">
<h2>Paper Details of: <i><xsl:value-of select="paper/title" /></i></h2>
<h3>Abstract</h3>
<p><xsl:value-of select="paper/Abstract" /></p>
<h3>Topics</h3>
<xsl:for-each select="paper/topics/topic">
<p><xsl:value-of select="name" /></p>
</xsl:for-each>
</xsl:template>

<xsl:template match="author/showpaper/success">
<h3>Your submitted papers:</h3>
<table class="table_with_decoration">
	<tr>
<th>Title</th>
<th>last edit</th>
<th>Version</th>
<th>State</th>

</tr>
	<xsl:for-each select="paper">
	<tr>
	<td> <xsl:value-of select="title"/></td>
	<td> <xsl:value-of select="last_edited"/></td>
	<td> <xsl:value-of select="version"/></td>
	<td> <xsl:value-of select="state"/></td>
	<td><a><xsl:attribute name="href">Author?action=updatepaper&amp;paper_id=<xsl:value-of select="id" /></xsl:attribute>update</a></td>
	<td><a><xsl:attribute name="href">Author?action=retractpaper&amp;paper_id=<xsl:value-of select="id" /></xsl:attribute>retract</a></td>
	<td><a><xsl:attribute name="href">Author?action=showpaper&amp;paper_id=<xsl:value-of select="id" /></xsl:attribute>more details</a></td>
</tr>
</xsl:for-each>
</table>
<h3><a href="Author?action=submitpaper">submit new paper</a></h3>
</xsl:template>
<xsl:template match="author/showpaper/failed">
<h3>An Error has occurred:</h3>
<xsl:value-of select="."/>
</xsl:template>

<xsl:template match="/author/submitpaper">

<h3>Step 1: Please fill out the form</h3>
<form action="Author" method="post" >
	<input type="hidden" name="action" value="processpaper"/>
	<table style="border:0">
		<tr>
			<td>Your Paper Title </td>
			<td>
				<input type="text" name="title" size="70">
					 <xsl:attribute name="value">
						 <xsl:value-of select="paper/title"/>
					 </xsl:attribute>
				 </input>
			</td>
		</tr>
		
		<tr>
			<td>Your Abstract</td>
			<td>	
				<textarea  name="abstract" cols="50" rows="10" >your abstract<xsl:value-of select="paper/abstract"/>
				  </textarea>
			</td>
		</tr>
		<tr>
		<td>Your Topics <xsl:value-of select="info"/></td>
		<td>
		<xsl:for-each select="topic">
<tr>
	
	<td>
		<input type="checkbox" name="topics">
			<xsl:attribute name="value">
        		<xsl:value-of select="id"/>
        	</xsl:attribute>
		</input>
		<xsl:value-of select="name"/>
	</td>
</tr>
</xsl:for-each>
</td>
</tr>
		<tr>
			<td>
				<input class="submit-button" type="submit" value="go to upload form" />
			</td>
		</tr>
	</table>
</form>
</xsl:template>

<xsl:template match="author/writefile">
<h3>Step 2: Choose your paper</h3>
<form action="WriteFile" method="post" enctype="multipart/form-data" >
<table style="border:0">
<tr>
			<td>Choose your Paper</td>
			<td>
				<input  size="50" name="thefile" type="file"/>
			</td>
			</tr>
				<tr>
			<td>
				<input class="submit-button" type="submit" value="Submit your Paper" />
			</td>
		</tr>
				</table>
</form>
</xsl:template>

<xsl:template match="author/failed">
<h3>An Error has occurred,plaese check your data!</h3>
<div>
<xsl:value-of select="."/>
</div>
</xsl:template>
<xsl:template match="author/error">
<h3>Error: <xsl:value-of select="."/></h3>
</xsl:template>
<xsl:template match="author/success">
<h3>Info: <xsl:value-of select="."/></h3>

</xsl:template>



</xsl:stylesheet>