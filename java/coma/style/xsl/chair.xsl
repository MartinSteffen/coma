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
			<label for="first name">* first name: </label>
			<input style="margin-left:2cm" type="text" id="first name" name="first name" size="30" maxlength="30"/>
			<br/>
			<label for="last name">* last name: </label>
			<input style="margin-left:2.1cm" type="text" id="last name" name="last name" size="30" maxlength="30"/><br/>
			<label for="email">* e-mail address: </label>
			<input style="margin-left:0.76cm" type="text" id="email" name="email" size="30" maxlength="30"/><br/>
			<label for="text">comment: </label>
			<textarea id="text" style="margin-left:2.2cm" name="text" rows="3" cols="30" class="textarea"/><br/>
			invite as:
				<input type="radio" name="invite as" value="author" checked=""/>author
				<input type="radio" name="invite as" value="reviewer"/>reviewer<br/>		
			<input type="submit" value="send" class="submit-button"/>
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
<xsl:value-of select='.'/><br></br> 
</xsl:template>

<xsl:template match="/result/setup/content">
<div class="formular">
<h3 align="middle">Setup of the Conference</h3><br/>
<form action="chair?action=send_setup" method="post">
<b>General</b><br/>
<label for="conference name">* conference name: </label>
<input style="margin-left:3.5cm" type="text" id="conference name" name="conference name" size="30" maxlength="30"/><br/>

<label for="homepage">* Homepage: </label>
<input style="margin-left:5.3cm" type="text" id="homepage" name="homepage" size="30" maxlength="30"/><br/>

<label for="description">description: </label>
<textarea id="description" style="margin-left:5.65cm" name="description" rows="3" cols="30" class="textarea"/><br/>

<label for="min_reviewers">* minimum reviewers per paper:</label>
<input id="min_reviewers" style="margin-left:0.1cm" name="min_reviewers" rows="3" cols="5" class="textarea"/><br/>

<b>Time</b><br/>
<label for="start">* conference start: </label>
<input style="margin-left:3.8cm" type="text" id="start_day" name="start_day" size="2" maxlength="2"/>.
<input type="text" id="start_month" name="start_day" size="2" maxlength="2"/>.
<input type="text" id="start_year" name="start_day" size="4" maxlength="4"/><br/>

<label for="end">* conference end: </label>
<input style="margin-left:4.02cm" type="text" id="end_day" name="start_day" size="2" maxlength="2"/>.
<input type="text" id="end_month" name="start_day" size="2" maxlength="2"/>.
<input type="text" id="end_year" name="start_day" size="4" maxlength="4"/><br/>
<b>Deadlines</b><br/>
<label for="abstract">* abstract submission deadline: </label>
<input style="margin-left:0.25cm" type="text" id="abstract" name="abstract" size="30" maxlength="30"/><br/>

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