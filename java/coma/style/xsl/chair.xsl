<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" indent="yes"  doctype-public= "-//W3C//DTD XHTML 1.1//EN" 
doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="iso-8859-1"/>
<xsl:include href="navcolumn.xsl"/>
<xsl:include href="chair_setup.xsl"/>

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
<xsl:apply-templates select = "/result/login/status"/>
<xsl:apply-templates select = "/result/invite/status"/>
<xsl:apply-templates select = "/result/showpapers/status"/>
<xsl:apply-templates select = "/result/showauthors/status"/>
<xsl:apply-templates select = "/result/showreviewers/status"/>
<xsl:apply-templates select = "/result/showreviewers_data/status"/>
<xsl:apply-templates select = "/result/showauthors_data/status"/>
<xsl:apply-templates select = "/result/setup/status"/>
<xsl:apply-templates select = "/result/setup_new_step1/status"/>
<xsl:apply-templates select = "/result/setup_new_step2/status"/>
<xsl:apply-templates select = "/result/email/status"/>
<xsl:apply-templates select = "/result/invitation_send/status"/>
</div>


<!-- Site navigation menu -->
<xsl:call-template name="navcolumn"/>

<!-- Main content -->
<div class="content">
<xsl:call-template name="setup"/>
<xsl:apply-templates select = "/result/login/content"/>
<xsl:apply-templates select = "/result/invite/content"/>
<xsl:apply-templates select = "/result/showpapers/content"/>
<xsl:apply-templates select = "/result/showauthors/content"/>
<xsl:apply-templates select = "/result/showreviewers/content"/>
<xsl:apply-templates select = "/result/showreviewers_data/content"/>
<xsl:apply-templates select = "/result/showauthors_data/content"/>
<xsl:apply-templates select = "/result/email/content"/>
</div> <!-- Main content end -->

</body>
</html>
</xsl:template>

<xsl:template match="/result/login/content">
<xsl:value-of select='.'/><br></br>
</xsl:template>

<xsl:template match="/result/invite/content">
	<div class="formular">
		<form action="Chair?action=send_invitation" method="post">
		<table style="color:black">
			<tr>
				<td>
					<label for="first name">* first name: </label>
				</td>
				<td>
					<input type="text" id="first name" name="first name" size="30" maxlength="30">
						<xsl:attribute name = "value"><xsl:apply-templates select="/result/invite/content/first"/>
						</xsl:attribute>
					</input>	
				</td>
			</tr>
			<tr>
				<td>
					<label for="last name">* last name: </label>
				</td>
				<td>
					<input type="text" id="last name" name="last name" size="30" maxlength="30">
						<xsl:attribute name = "value"><xsl:apply-templates select="/result/invite/content/last"/>
						</xsl:attribute>
					</input>
				</td>
			</tr>
			<tr>
				<td>
					<label for="email">* e-mail address: </label>
				</td>
				<td>
					<input type="text" id="email" name="email" size="30" maxlength="30">
						<xsl:attribute name = "value"><xsl:apply-templates select="/result/invite/content/email"/>
						</xsl:attribute>
					</input>
				</td>
			</tr>
			<tr>
				<td>
					<label for="text">comment: </label>
				</td>
				<td>
					<textarea name="text" rows="3" cols="30">&#160;</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					invite as:
					<input type="radio" name="invite as" value="author" checked=""/>author
					<input type="radio" name="invite as" value="reviewer"/>reviewer
				</td>
			</tr>
			<tr>
				<td colspan="2">		
					<input type="submit" value="send" class="submit-button"/>
				</td>
			</tr>
		</table>
		</form>
	</div>
</xsl:template>

<xsl:template match="/result/showauthors/content">
	<table class="chair" cellpadding="5">
		<thead>
			<tr align="center">
				<th>first name</th>
				<th>last name</th>
				<th>email</th>
				<th colspan="3">options</th>
				<th></th>
			</tr>
		</thead>
	<xsl:for-each select="/result/showauthors/content/person">
		<tr>
			<td><xsl:value-of select="first_name"/></td>
			<td><xsl:value-of select="last_name"/></td>
			<td><xsl:value-of select="email"/></td>
			<td><a><xsl:attribute name = "href">Chair?action=show_authors&amp;delete=true</xsl:attribute>delete
			</a>
			</td>
			<td>
			<a><xsl:attribute name = "href">Chair?action=email&amp;email=<xsl:value-of select="email"/></xsl:attribute>write email</a>
			</td>
			<td>
			<a><xsl:attribute name = "href">Chair?action=show_authors&amp;id=<xsl:value-of select="id"/></xsl:attribute>statistic</a>
			</td>
		</tr>
	</xsl:for-each>
	</table>
</xsl:template>

<xsl:template match="/result/showauthors_data/content">
	<table class="chair" cellpadding="5">
		<thead>
			<tr align="center">
				<th colspan="2">personel data</th>
				<xsl:if test="//showauthors_data/content/paper"><th colspan="2">papers</th></xsl:if>
			</tr>
		</thead>
	<xsl:for-each select="/result/showauthors_data/content/person">
		<tr>
			<td>
				first name: 
			</td>
			<td>
				<xsl:value-of select="first_name"/>
			</td>
			<xsl:if test="//showauthors_data/content/paper">
			<td colspan="2" rowspan="9">
				<table class="chair">
					<xsl:for-each select="/result/showauthors_data/content/paper">
						<tr>
							<td>
								title:
							</td>
							<td width="200">
								<xsl:value-of select="title"/>
							</td>
						</tr>
						<tr>
							<td>
								Abstract:
							</td>
							<td width="100">
								<xsl:value-of select="Abstract"/>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								-----------------------------------------------
							</td>
						</tr>
					</xsl:for-each>
				</table>
			</td>
			</xsl:if>
		</tr>	
		<tr>
			<td>last name: 
			</td>
			<td><xsl:value-of select="last_name"/>
			</td>
		</tr>	
		<tr>
			<td>affiliation: 
			</td>
			<td><xsl:value-of select="affiliation"/>
			</td>
		</tr>	
		<tr>
			<td>email 
			</td>
			<td><xsl:value-of select="email"/>
			</td>
		</tr>	
		<tr>
			<td>phone number: 
			</td>
			<td><xsl:value-of select="phone_number"/>
			</td>
		</tr>	
		<tr>
			<td>fax number: 
			</td>
			<td><xsl:value-of select="fax_number"/>
			</td>
		</tr>	
		<tr>
			<td>street 
			</td>
			<td><xsl:value-of select="street"/>
			</td>
		</tr>	
		<tr>
			<td>city: 
			</td>
			<td><xsl:value-of select="postal_code"/>&#160;<xsl:value-of select="city"/>
			</td>
		</tr>	
		<tr>
			<td>country: 
			</td>
			<td><xsl:value-of select="country"/>
			</td>
		</tr>
	</xsl:for-each>
	</table>
</xsl:template>

<xsl:template match="/result/showreviewers/content">
	<table class="chair" cellpadding="5">
		<thead>
			<tr align="center">
				<th>first name</th>
				<th>last name</th>
				<th>email</th>
				<th colspan="3">options</th>
				<th></th>
			</tr>
		</thead>
	<xsl:for-each select="/result/showreviewers/content/person">
		<tr>
			<td><xsl:value-of select="first_name"/></td>
			<td><xsl:value-of select="last_name"/></td>
			<td><xsl:value-of select="email"/></td>
			<td><a><xsl:attribute name = "href">Chair?action=show_reviewers&amp;delete=true</xsl:attribute>delete
			</a>
			</td>
			<td>
			<a><xsl:attribute name = "href">Chair?action=email&amp;email=<xsl:value-of select="email"/></xsl:attribute>write email</a>
			</td>
			<td>
			<a><xsl:attribute name = "href">Chair?action=show_reviewers&amp;id=<xsl:value-of select="id"/></xsl:attribute>statistic</a>
			</td>
		</tr>
	</xsl:for-each>
	</table>
</xsl:template>


<xsl:template match="/result/showreviewers_data/content">
	Hier noch Liste der Paper und zugehörigen Review Reports auf der rechten Seite eintragen
	<table class="chair" cellpadding="5">
		<thead>
			<tr align="center">
				<th></th>
				<th></th>
			</tr>
		</thead>
	<xsl:for-each select="/result/showreviewers_data/content/person">
		<tr>
			<td>title: 
			</td>
			<td><xsl:value-of select="title"/>
			</td>
		</tr>
		<tr>
			<td>first name: 
			</td>
			<td><xsl:value-of select="first_name"/>
			</td>
		</tr>	
		<tr>
			<td>last name: 
			</td>
			<td><xsl:value-of select="last_name"/>
			</td>
		</tr>	
		<tr>
			<td>affiliation: 
			</td>
			<td><xsl:value-of select="affiliation"/>
			</td>
		</tr>	
		<tr>
			<td>email 
			</td>
			<td><xsl:value-of select="email"/>
			</td>
		</tr>	
		<tr>
			<td>phone number: 
			</td>
			<td><xsl:value-of select="phone_number"/>
			</td>
		</tr>	
		<tr>
			<td>fax number: 
			</td>
			<td><xsl:value-of select="fax_number"/>
			</td>
		</tr>	
		<tr>
			<td>street 
			</td>
			<td><xsl:value-of select="street"/>
			</td>
		</tr>	
		<tr>
			<td>city: 
			</td>
			<td><xsl:value-of select="postal_code"/><xsl:value-of select="city"/>
			</td>
		</tr>	
		<tr>
			<td>country: 
			</td>
			<td><xsl:value-of select="country"/>
			</td>
		</tr>
	</xsl:for-each>
	</table>

</xsl:template>


<xsl:template match="/result/showpapers/content">
Hier noch neben Autorenliste auch Reviewreports und Bewertung anzeigen lassen 
<table class="chair" cellpadding="2">
<thead>
<tr align="center">
	<!--<td>ID</td><td>Conference ID</td><td>Author ID</td>-->
	<th>Title</th>
	<th>Abstract</th>
	<th>last edited</th>
	<th>Version</th>
	<th>filename</th>
	<th>state</th>
	<th></th>
</tr>
</thead>
<xsl:for-each select="/result/showpapers/content/paper">
<tr>
	<td><xsl:value-of select="title"/></td>
	<td><xsl:value-of select="Abstract"/></td>
	<td><xsl:value-of select="last_edited"/></td>
	<td><xsl:value-of select="version"/></td>
	<td>
		<a>
			<xsl:attribute name = "href"><xsl:value-of select="filename"/>.<xsl:value-of select="mim_type"/>
			</xsl:attribute>
			<xsl:value-of select="filename"/>.<xsl:value-of select="mim_type"/>
			</a>
	</td>
   <td>
   	<xsl:choose>
   	<xsl:when test="state ='0'">
   		rejected
   	</xsl:when>
   	<xsl:when test="state ='1'">
   		checked
   	</xsl:when>
   	</xsl:choose>
   </td>
   <td>
   	<a>
		<xsl:attribute name = "href">Chair?action=show_authors&amp;id=<xsl:value-of select="author_id"/></xsl:attribute>
		Autorenliste
		</a>
   </td>
</tr>
</xsl:for-each>
</table>
</xsl:template>


<xsl:template match="/result/login/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/invite/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/showauthors/status">
<xsl:value-of select='.'/> 
</xsl:template>

<xsl:template match="/result/showreviewers/status">
<xsl:value-of select='.'/> 
</xsl:template>

<xsl:template match="/result/showpapers/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/setup_new/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/setup/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/email/status">
<xsl:value-of select='.'/><br></br> 
</xsl:template>


</xsl:stylesheet>