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
</div><!-- Site heading -->



<!-- Site navigation menu -->
<div class="navbar">
<ul>
  <li><a href="Index">Home page</a></li>
  
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

	

</div><!-- Site navigation menu -->

<!-- Main content -->
<div class="content">
<xsl:apply-templates/>
</div> <!-- Main content -->
</body>
</html>
</xsl:template>

<xsl:template match="/result/status">
<h3><xsl:apply-templates/></h3>
</xsl:template>

<xsl:template match="/result/myform">
<h3>Fill out the form to subscribe</h3>
	<form method="post" action="Subscribe" >
		<table border="0">
			<tr>
				<td>First name: </td>
				<td>
					<input type="text" name="first_name" size="20"/>
				</td>
			</tr>
			<tr>
				<td >Last name: </td>
				<td >
					<input type="text" name="last_name" size="20" />
				</td>
			</tr>
			<tr>
				<td >Title: </td>
				<td >
					<input type="text" name="title" size="20" />
				</td>
			</tr>
			<tr>
				<td >Affiliation: </td>
				<td >
					<input type="text" name="affiliation" size="20" />
				</td>
			</tr>
			<tr>
				<td>Your email: </td>
		  	<td>
		  		<input type="text" name="email" size="20" />
				</td>
			</tr>
			<tr>
				<td >Phone number: </td>
				<td >
					<input type="text" name="phone_number" size="20" />
				</td>
			</tr>
			<tr>
				<td >Fax number: </td>
				<td >
					<input type="text" name="fax_number" size="20" />
				</td>
			</tr>
			<tr>
				<td >Street: </td>
				<td >
					<input type="text" name="street" size="20" />
				</td>
			</tr>
			<tr>
				<td >Postal code: </td>
				<td >
					<input type="text" name="postal_code" size="20" />
				</td>
			</tr>
			<tr>
				<td >City: </td>
				<td >
					<input type="text" name="city" size="20" />
				</td>
			</tr>
			<tr>
				<td >State: </td>
				<td >
					<input type="text" name="state" size="20" />
				</td>
			</tr>
			<tr>
				<td >Country: </td>
				<td >
					<input type="text" name="country" size="20" />
				</td>
			</tr>
			<tr>
				<td >Password: </td>
				<td >
					<input type="password" name="password" size="20" />
				</td>
			</tr>
			<tr>
				<td > Re-type password: </td>
				<td >
					<input type="password" name="repassword" size="20" />
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" value="Submit Info" />
				</td>
			</tr>
		</table>
	</form>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="result/person">
<table border="1">
      <tr bgcolor="#9acd32">
        <th>key</th>
        <th>value</th>
      </tr>
      
      <tr>
        <td><xsl:apply-templates/></td>
      </tr>
      
</table>
</xsl:template>


</xsl:stylesheet>