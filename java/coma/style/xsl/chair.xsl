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
<xsl:apply-templates select = "/result/login/content"/>
<xsl:apply-templates select = "/result/invite/content"/>
<xsl:apply-templates select = "/result/showpapers/content"/>
<xsl:apply-templates select = "/result/showauthors/content"/>
<xsl:apply-templates select = "/result/showreviewers/content"/>
<xsl:apply-templates select = "/result/showreviewers_data/content"/>
<xsl:apply-templates select = "/result/showauthors_data/content"/>
<xsl:apply-templates select = "/result/setup/content"/>
<xsl:apply-templates select = "/result/setup_new_step1/content"/>
<xsl:apply-templates select = "/result/setup_new_step2/content"/>
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
				<th colspan="2">papers</th>
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


<xsl:template match="/result/setup/content">
<table class="chair" cellpadding="2">
<tr>
	<td>
	Name:
	</td>
	<td width="200"> 
		<xsl:apply-templates select="/result/setup/content/Conference/name"/>
	</td>
</tr>
<tr>
	<td>
	Homepage: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference/homepage"/>
	</td>
</tr>
<tr>
	<td>
	Description:
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference/description"/>
	</td>
</tr>
<tr>
	<td>
	min reviewer per paper::
	</td>
	<td> 
		  <xsl:apply-templates select="/result/setup/content/Conference/min"/>
	</td>
</tr>
<tr>
	<td>
	Start:
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference/start"/>
	</td>
</tr>
<tr>
	<td>
	End: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference/end"/>
	</td>
</tr>
<tr>
	<td>
	Notification:
	</td>
	<td> 
		 <xsl:apply-templates select="/result/setup/content/Conference/notification"/>
	</td>
</tr>
<tr>
	<td colspan="2">
	<h4 style="color:black">Deadlines</h4>
	</td>
</tr>
<tr>
	<td>
	Abstract submission: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference//abstract"/>
	</td>
</tr>
<tr>
	<td>
	Paper submission: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference//paper"/>
	</td>
</tr>
<tr>
	<td>
	review: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference//review"/>
	</td>
</tr>
<tr>
	<td>
	final submission:
	</td>
	<td> 
		 <xsl:apply-templates select="/result/setup/content/Conference//final"/>
	</td>
</tr>
<tr>
	<td colspan="2">
	<h4 style="color:black">Topics</h4>
	</td>
</tr>
<xsl:for-each select="/result/setup/content/topic">
<tr>
	<td colspan="2">
	<xsl:value-of select="name"/>
	</td>
</tr>
</xsl:for-each>
</table>
</xsl:template>


<xsl:template match="/result/setup_new_step1/content">
<div class="formular">
Step 1
<form action="Chair?action=send_setup&amp;step=1" method="post">
<table style="color:black">
<tr>
	<td colspan="2">
		<b>General</b><br/>
	</td>
</tr>
<tr>
	<td width="300">
		<label for="conference name">* conference name: </label>
	</td>
	<td>
		<input type="text" id="conference name" name="conference name" size="30" maxlength="30">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/name"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
<tr>
	<td>
		<label for="homepage">* Homepage: </label>
	</td>
	<td>
		<input type="text" id="homepage" name="homepage" size="30" maxlength="30">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/home"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
<tr>
	<td>
		<label for="description">description: </label>
	</td>
	<td>
		<textarea id="description" name="description" rows="3" cols="30" class="textarea">&#160;</textarea>
	</td>
</tr>
<tr>
	<td valign="middle">
		<label for="min_reviewers">* minimum reviewers per paper:</label>
	</td>
	<td>
		<input id="min_reviewers" name="min_reviewers" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/min"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
<tr>
	<td>
		<label for="topics">* number of topics</label>
	</td>
	<td>
		<input id="topics" name="topics" size="3" maxlength="3">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/topics"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
<tr>
	<td colspan="2">
		<b>Time</b>
	</td>
</tr>
<tr>
	<td>
		<label for="start">* conference start: </label>
	</td>
	<td>
		<input type="text" id="start_day" name="start_day" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/start_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="start_month" name="start_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/start_month"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="start_year" name="start_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/start_year"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>	
<tr>
	<td>
		<label for="end">* conference end: </label>
	</td>
	<td>
		<input type="text" id="end_day" name="end_day" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/end_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="end_month" name="end_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/end_month"/>
  			</xsl:attribute>
		</input>:
		<input type="text" id="end_year" name="end_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/end_year"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
<tr>
	<td>
		<label for="notification">* notification: </label>
	</td>
	<td>
		<input type="text" id="not_day" name="not_day" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/not_day"/>
  			</xsl:attribute>
		</input>:
		<input type="text" id="not_month" name="not_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/not_month"/>
  			</xsl:attribute>
		</input>:
		<input type="text" id="not_year" name="not_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/not_year"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>	
<tr>
	<td colspan="2">
		<b>Deadlines</b>
	</td>
</tr>
<tr>
	<td>
		<label for="abstract">* abstract submission deadline: </label>
	</td>
	<td>
		<input type="text" id="abstract_day" name="abstract_day" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/abstract_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="abstract_month" name="abstract_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/abstract_month"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="abstract_year" name="abstract_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/abstract_year"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
<tr>
	<td>
		<label for="paper">* paper submission deadline: </label>
	</td>
	<td>
		<input type="text" id="paper_day" name="paper_day" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/paper_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="paper_month" name="paper_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/paper_month"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="paper_year" name="paper_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/paper_year"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
<tr>
	<td>
		<label for="review">* review deadline: </label>
	</td>
	<td>
		<input type="text" id="review_day" name="review_day" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/review_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="review_month" name="review_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/review_month"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="review_year" name="review_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/review_year"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
<tr>
	<td>
		<label for="final">* final version deadline: </label>
	</td>
	<td>
		<input type="text" id="final_day" name="final_day" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/final_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="final_month" name="final_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/final_month"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="final_year" name="final_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup_new_step1/content/final_year"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
</table>
<input type="submit" value="forward to step 2" class="submit-button"/>
</form>
</div>
</xsl:template>


<xsl:template match="/result/setup_new_step2/content">
<div class="formular">
Step 2 : Please insert topics
<form action="Chair?action=send_setup&amp;step=2" method="post">
<table class="chair">
<xsl:for-each select="/result/setup_new_step2/content/topic">
<tr>
	<td>
	<input type="text" size="30" maxlength="30">
	<xsl:attribute name="name">topic<xsl:value-of select="number"/></xsl:attribute></input>
	</td>
</tr>
</xsl:for-each>
</table>
<input type="submit" value="save configuration" class="submit-button"/>
</form>
</div>
</xsl:template>





<!-- content for email -->
<xsl:template match="/result/email/content">
<form action="Chair?action=send_email" method="post">
<table style="color:black">
<tr>
	<td>
		To:
	</td>
	<td>	
		<input type="text" name="Recv" id="Recv"  size="24">
			<xsl:attribute name="value">
  				<xsl:value-of select="/result/email/content/recv"/> 
  			</xsl:attribute>
		</input>
	</td>
</tr>
<tr>
	<td>
		Subject: 
	</td>
	<td>
		<input type="text" id="Subj" name="Subj" size="24">
			<xsl:attribute name="value">
  				<xsl:value-of select="/result/email/content/subj"/>
  		  	</xsl:attribute>
  		</input>
  	</td>
</tr>
<tr>
	<td>
	</td>
	<td>
		<textarea rows="7" name="Cont" id="Cont" cols="28">
  			<xsl:value-of select="/result/email/content/cont"/>
		</textarea>
	</td>
</tr>
<tr>
	<td>
		<input type="submit" value="Send" name="B1"/>
	</td>
	<td>
		<input type="reset" value="Delete" name="B2"/>
	</td>
</tr>
</table>
</form>
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