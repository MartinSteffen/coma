<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" indent="yes"/>

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
<xsl:apply-templates select = "/result/setup_new/status"/>
<xsl:apply-templates select = "/result/email/status"/>
<xsl:apply-templates select = "/result/invitation_send/status"/>
</div>


<!-- Site navigation menu -->
<div class="navbar">
<ul>
  <li><a href="index.html">Home page</a></li>
  <li>
   <form action="Chair?action=setup" method="post">
  		<input type="submit" value="setup" class="submit-button" />
  	</form>
  <form action="Chair?action=invite_person" method="post">
  		<input type="submit" value="invite person" class="submit-button" />
  	</form>
  <form action="Chair?action=show_authors" method="post">
  		<input type="submit" value="show authors" class="submit-button" />	
  	</form>
  	<form action="Chair?action=show_reviewers" method="post">
  		<input type="submit" value="show reviewers" class="submit-button" />	
  	</form>
  	 <form action="Chair?action=show_papers" method="post">
  		<input type="submit" value="show papers" class="submit-button" />
  	</form>
  	<form action="Chair?action=email" method="post">
  		<input type="submit" value="email" class="submit-button" />	
  	</form>
  </li>
		
  <li><a href="http://snert.informatik.uni-kiel.de:8888/coma/">tomcat directory</a></li>
  <li><a href="http://snert.informatik.uni-kiel.de:8080/svn/coma/">svn repository</a></li>
  <li><a href="http://snert.informatik.uni-kiel.de:8080/~wprguest3/phpmyadmin/">phpMyAdmin</a></li>
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
<xsl:apply-templates select = "/result/login/content"/>
<xsl:apply-templates select = "/result/invite/content"/>
<xsl:apply-templates select = "/result/showpapers/content"/>
<xsl:apply-templates select = "/result/showauthors/content"/>
<xsl:apply-templates select = "/result/showreviewers/content"/>
<xsl:apply-templates select = "/result/showreviewers_data/content"/>
<xsl:apply-templates select = "/result/showauthors_data/content"/>
<xsl:apply-templates select = "/result/setup/content"/>
<xsl:apply-templates select = "/result/setup_new/content"/>
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
					<textarea id="text" name="text" rows="3" cols="30" class="textarea"/>
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
	<table style="color:black;text-align:center;font-size:12pt" cellpadding="5">
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
Hier noch Liste der Paper mit Link auf der rechten Seite eintragen
	<table style="color:black;text-align:center;font-size:12pt" cellpadding="5">
		<thead>
			<tr align="center">
				<th></th>
				<th></th>
			</tr>
		</thead>
	<xsl:for-each select="/result/showauthors_data/content/person">
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

<xsl:template match="/result/showreviewers/content">
	<table style="color:black;text-align:center;font-size:12pt" cellpadding="5">
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
	<table style="color:black;text-align:center;font-size:12pt" cellpadding="5">
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
<table style="color:black;text-align:center;;font-size:12pt" cellpadding="5">
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
Name: <xsl:apply-templates select="/result/setup/content/name"/><br/>
Homepage: <xsl:apply-templates select="/result/setup/content/home"/><br/>
Description: <xsl:apply-templates select="/result/setup/content/desc"/><br/>
Start: <xsl:apply-templates select="/result/setup/content/start"/><br/>
End: <xsl:apply-templates select="/result/setup/content/end"/><br/>
Notification: <xsl:apply-templates select="/result/setup/content/not"/><br/>
<h3 style="color:black">Deadlines</h3>
Abstract submission: <xsl:apply-templates select="/result/setup/content/abstract"/><br/>
Paper submission: <xsl:apply-templates select="/result/setup/content/paper"/><br/>
review: <xsl:apply-templates select="/result/setup/content/review"/><br/>
final submission: <xsl:apply-templates select="/result/setup/content/final"/><br/>
min reviewer per paper: <xsl:apply-templates select="/result/setup/content/min"/><br/>
</xsl:template>


<xsl:template match="/result/setup_new/content">
<div class="formular">
<form action="Chair?action=send_setup" method="post">
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
  				<xsl:apply-templates select="/result/setup/content/name"/>
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
  				<xsl:apply-templates select="/result/setup/content/home"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
<tr>
	<td>
		<label for="description">description: </label>
	</td>
	<td>
		<textarea id="description" name="description" rows="3" cols="30" class="textarea"/>
	</td>
</tr>
<tr>
	<td valign="middle">
		<label for="min_reviewers">* minimum reviewers per paper:</label>
	</td>
	<td>
		<input id="min_reviewers" name="min_reviewers" size="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/min"/>
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
  				<xsl:apply-templates select="/result/setup/content/start_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="start_month" name="start_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/start_month"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="start_year" name="start_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/start_year"/>
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
  				<xsl:apply-templates select="/result/setup/content/end_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="end_month" name="end_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/end_month"/>
  			</xsl:attribute>
		</input>:
		<input type="text" id="end_year" name="end_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/end_year"/>
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
  				<xsl:apply-templates select="/result/setup/content/not_day"/>
  			</xsl:attribute>
		</input>:
		<input type="text" id="not_month" name="not_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/not_month"/>
  			</xsl:attribute>
		</input>:
		<input type="text" id="not_year" name="not_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/not_year"/>
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
  				<xsl:apply-templates select="/result/setup/content/abstract_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="abstract_month" name="abstract_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/abstract_month"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="abstract_year" name="abstract_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/abstract_year"/>
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
  				<xsl:apply-templates select="/result/setup/content/paper_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="paper_month" name="paper_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/paper_month"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="paper_year" name="paper_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/paper_year"/>
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
  				<xsl:apply-templates select="/result/setup/content/review_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="review_month" name="review_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/review_month"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="review_year" name="review_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/review_year"/>
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
  				<xsl:apply-templates select="/result/setup/content/final_day"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="final_month" name="final_month" size="2" maxlength="2">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/final_month"/>
  			</xsl:attribute>
  		</input>:
		<input type="text" id="final_year" name="final_year" size="4" maxlength="4">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/final_year"/>
  			</xsl:attribute>
  		</input>
	</td>
</tr>
</table>
<input type="submit" value="save" class="submit-button"/>
</form>
</div>
</xsl:template>



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