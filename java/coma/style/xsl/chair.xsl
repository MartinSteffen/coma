<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:output method="xml" indent="yes"  doctype-public= "-//W3C//DTD XHTML 1.1//EN" 
doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="iso-8859-1"/>

<xsl:template match="/">
<html> 
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
<xsl:apply-templates select = "/result/setup/status"/>
<xsl:apply-templates select = "/result/email/status"/>
<xsl:apply-templates select = "/result/invitation_send/status"/>
</div>


<!-- Site navigation menu -->
<div class="navbar">
<ul>
  <li><a href="index.html">Home page</a></li>
  <li>
   <form action="chair?action=setup" method="post">
  		<input type="submit" value="setup" class="submit-button" />
  	</form>
  <form action="chair?action=invite_person" method="post">
  		<input type="submit" value="invite person" class="submit-button" />
  	</form>
  <form action="chair?action=show_authors" method="post">
  		<input type="submit" value="show authors" class="submit-button" />	
  	</form>
  	<form action="chair?action=show_reviewers" method="post">
  		<input type="submit" value="show reviewers" class="submit-button" />	
  	</form>
  	 <form action="chair?action=show_papers" method="post">
  		<input type="submit" value="show papers" class="submit-button" />
  	</form>
  	<form action="chair?action=email" method="post">
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
<xsl:apply-templates select = "/result/setup/content"/>
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
		<form action="chair?action=send_invitation" method="post">
		<table style="color:black">
			<tr>
				<td>
					<label for="first name">* first name: </label>
				</td>
				<td>
					<input type="text" id="first name" name="first name" size="30" maxlength="30">
						<!-- Wie krieg ich diesen Wert in das Formularfeld?
						<xsl:apply-templates select="/result/invite/content/first"/>	--></input>
				</td>
			</tr>
			<tr>
				<td>
					<label for="last name">* last name: </label>
				</td>
				<td>
					<input type="text" id="last name" name="last name" size="30" maxlength="30"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="email">* e-mail address: </label>
				</td>
				<td>
					<input type="text" id="email" name="email" size="30" maxlength="30"/>
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
<xsl:value-of select='.'/><br></br> 
</xsl:template>

<xsl:template match="/result/showreviewers/content">
<xsl:value-of select='.'/><br></br> 
</xsl:template>

<xsl:template match="/result/showpapers/content">
<table style="color:black; text-align:center">
<tr align="center">
	<td>ID</td><td>Conference ID</td><td>Author ID</td><td>Title</td>
	<td>Abstract</td><td>last edited</td><td>Version</td><td>filename</td>
	<td>state</td><td>mim_type</td>
</tr>
<xsl:for-each select="/result/showpapers/content/paper">
<tr>
	<td><xsl:value-of select="id"/></td>	
	<td><xsl:value-of select="conference_id"/></td>
	<td><xsl:value-of select="author_id"/></td>
	<td><xsl:value-of select="title"/></td>
	<td><xsl:value-of select="Abstract"/></td>
	<td><xsl:value-of select="last_edited"/></td>
	<td><xsl:value-of select="version"/></td>
	<td><xsl:value-of select="filename"/></td>
	<td><xsl:value-of select="state"/></td>
	<td><xsl:value-of select="mim_type"/></td>
</tr>
</xsl:for-each>
</table>
</xsl:template>

<xsl:template match="/result/setup/content">
<div class="formular">
<h3 align="middle">Setup of the Conference</h3><br/>
<form action="chair?action=send_setup" method="post">
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
		<input type="text" id="conference name" name="conference name" size="30" maxlength="30"/>
	</td>
</tr>
<tr>
	<td>
		<label for="homepage">* Homepage: </label>
	</td>
	<td>
		<input type="text" id="homepage" name="homepage" size="30" maxlength="30"/><br/>
	</td>
</tr>
<tr>
	<td>
		<label for="description">description: </label>
	</td>
	<td>
		<textarea id="description" name="description" rows="3" cols="30" class="textarea"/><br/>
	</td>
</tr>
<tr>
	<td valign="middle">
		<label for="min_reviewers">* minimum reviewers per paper:</label>
	</td>
	<td>
		<input id="min_reviewers" name="min_reviewers" rows="3" cols="5" class="textarea"/><br/>
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
		<input type="text" id="start_day" name="start_day" size="2" maxlength="2"/>.
		<input type="text" id="start_month" name="start_day" size="2" maxlength="2"/>.
		<input type="text" id="start_year" name="start_day" size="4" maxlength="4"/>
	</td>
</tr>	
<tr>
	<td>
		<label for="end">* conference end: </label>
	</td>
	<td>
		<input type="text" id="end_day" name="start_day" size="2" maxlength="2"/>.
		<input type="text" id="end_month" name="start_day" size="2" maxlength="2"/>.
		<input type="text" id="end_year" name="start_day" size="4" maxlength="4"/>
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
		<input type="text" id="abstract" name="abstract" size="30" maxlength="30"/>
	</td>
</tr>
</table>

<label for="paper">* paper submission deadline: </label>
<input style="margin-left:0.87cm" type="text" id="paper" name="paper" size="30" maxlength="30"/><br/>

<label for="review">* review deadline: </label>
<input style="margin-left:3.81cm" type="text" id="review" name="review" size="30" maxlength="30"/><br/>

<label for="final">* final version deadline: </label>
<input style="margin-left:2.25cm" type="text" id="final" name="final" size="30" maxlength="30"/><br/>
<input type="submit" value="save" class="submit-button"/>
</form>

</div>
</xsl:template>

<xsl:template match="/result/email/content">
<form action="chair?action=send_email" method="post">

	<p style="text-align:center">To: 	
		<input type="text" name="Recv" id="Recv"  size="24">
			<xsl:attribute name="value">
  				<xsl:value-of select="/result/recv"/> 
  			</xsl:attribute>
		</input>
	</p>
	<p style="text-align:center">  
		Subject: <input type="text" id="Subj" name="Subj" size="24">
					<xsl:attribute name="value">
  						<xsl:value-of select="/result/subj"/>
  		  			</xsl:attribute>
  					</input>
  	</p>
	<p style="text-align:center">
		<textarea rows="7" name="Cont" id="Cont" cols="28">
  			<xsl:value-of select="/result/cont"/>
		</textarea></p>
	<p style="text-align:center"><input type="submit" value="Absenden" name="B1"/></p>
</form>
  
<xsl:value-of select='.'/><br></br> 
</xsl:template>



<xsl:template match="/result/login/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/invite/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/showauthors/status">
<xsl:value-of select='.'/><br></br> 
</xsl:template>

<xsl:template match="/result/showreviewers/status">
<xsl:value-of select='.'/> 
</xsl:template>

<xsl:template match="/result/showpapers/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/setup/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/email/status">
<p style="text-align:center">	

<xsl:value-of select='.'/><br></br> 
	</p>
</xsl:template>



</xsl:stylesheet>