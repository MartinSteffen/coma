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


</div> <!-- Main content end -->

</body>
</html>
</xsl:template>


<xsl:template match="author/showpaper/success">
<h3>Your submitted papers:</h3>
<xsl:value-of select="."/>
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
				<input class="input-box" type="text" name="title" size="70">
					 <xsl:attribute name="value">
						 <xsl:value-of select="title"/>
					 </xsl:attribute>
				 </input>
			</td>
		</tr>
		
		<tr>
			<td>Enter here your Abstract</td>
			<td>	
				<textarea class="input-box" name="abstract" cols="50" rows="10" >
                                  <!-- <xsl:attribute name="value"> -->
						 <xsl:value-of select="abstract"/>
                                                 <!-- </xsl:attribute> -->
                                         _
				 </textarea>
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
</xsl:template>
<xsl:template match="author/error">
<div>
<xsl:value-of select="."/> 
</div>
</xsl:template>
<xsl:template match="author/success">
<h3>Your Paper is successfully added to your database!</h3>
<div>
<xsl:value-of select="."/> 
</div>
</xsl:template>



</xsl:stylesheet>