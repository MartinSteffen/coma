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


<!-- Site navigation menu -->
<xsl:call-template name="navcolumn"/>

<!-- Main content -->
<div class="content">
<xsl:call-template name="setup_conference"/>
<xsl:call-template name="setup_topics"/>
<xsl:call-template name="add_topics"/>
<xsl:call-template name="save_initial"/>
<xsl:apply-templates select = "/result/login/content"/>
<xsl:apply-templates select = "/result/show_criterions/content"/>
<xsl:apply-templates select = "/result/invite/content"/>
<xsl:apply-templates select = "/result/show_papers/content"/>
<xsl:apply-templates select = "/result/showauthors/content"/>
<xsl:apply-templates select = "/result/showreviewers/content"/>
<xsl:apply-templates select = "/result/show_topics/content"/>
<xsl:apply-templates select = "/result/showreviewers_data/content"/>
<xsl:apply-templates select = "/result/showauthors_data/content"/>
<xsl:apply-templates select = "/result/criterion_add/content"/>
<xsl:apply-templates select = "/result/email/content"/>
<xsl:apply-templates select = "/result/assign/content"/>
<xsl:apply-templates select = "/result/criteria/content"/>
<xsl:apply-templates select = "/result/criterion_change/content"/>
<xsl:apply-templates select = "/result/program/content"/>
<xsl:apply-templates select = "/result/proshow/content"/>
<xsl:apply-templates select = "/result/statistic/content"/>
</div> 
<!-- Main content end -->

</body>
</html>
</xsl:template>

<xsl:template match="/result/login/content">
<xsl:apply-templates select = "/result/login/status"/><br/>
<xsl:if test="//abstract">
<xsl:value-of select="//abstract"/><br/>
</xsl:if>
<xsl:if test="//paper">
<xsl:value-of select="//paper"/><br/>
</xsl:if>
<xsl:if test="//review">
<xsl:value-of select="//review"/><br/>
</xsl:if>
<xsl:if test="//final">
<xsl:value-of select="//final"/><br/>
</xsl:if>
<xsl:if test="//select">
<xsl:value-of select="//select"/><br/>
</xsl:if>
</xsl:template>

<xsl:template match="/result/criterion_change/content">
<xsl:apply-templates select = "/result/criterion_change/status"/>
<form method="post">
<xsl:attribute name = "action">Chair?action=criterion&amp;target=criteria&amp;
criterion_target=update&amp;id=<xsl:value-of select="/result/criterion_change/content/id"/>
</xsl:attribute>
<table class="chair">
<tr>
	<td>
		*Name:
	</td>
	<td>
		<input type="text" size="30" maxlength="30" name="criterion_name" id="criterion_name">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/criterion_change/content/name"/>
			</xsl:attribute></input>
	</td>
</tr>
<tr>
	<td>
		*Description:
	</td>
	<td>
		<input type="text" size="50" maxlength="50" name="criterion_description" id="criterion_description">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/criterion_change/content/description"/>
			</xsl:attribute></input>
	</td>
</tr>
<tr>
	<td>
		*Max Value:
	</td>
	<td>
		<input type="text" size="2" maxlength="2" name="criterion_value" id="criterion_value">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/criterion_change/content/value"/>
			</xsl:attribute></input>
	</td>
</tr>
<tr>
	<td>
		*quality Ranking:
	</td>
	<td>
		<input type="text" size="2" maxlength="2" name="criterion_ranking" id="criterion_ranking">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/criterion_change/content/ranking"/>
			</xsl:attribute></input>
	</td>
</tr>	
</table>
<input type="submit" value="update" class="submit-button"/>
</form>
</xsl:template>

<xsl:template match="/result/statistic/content">
<h4 style="color_black">Statistic for this Conference</h4> 
<table class="chair" cellpadding="5">
<tr>
	<td align="left">
		Topics:
	</td>
	<td>
		<xsl:value-of select="/result/statistic/content/topics"/>
	</td>
</tr>
<tr>
	<td align="left">
		Criterions:
	</td>
	<td>
		<xsl:value-of select="/result/statistic/content/criterions"/>
	</td>
</tr>
<tr>
	<td align="left">
		Persons:
	</td>
	<td>
		<xsl:value-of select="/result/statistic/content/persons"/>
	</td>
</tr>
<tr>
	<td style="font-size:10pt">
		Authors:
	</td>
	<td>
		<xsl:value-of select="/result/statistic/content/authors"/>
	</td>
</tr>
<tr>
	<td style="font-size:10pt">
		Reviewers:
	</td>
	<td>
		<xsl:value-of select="/result/statistic/content/reviewers"/>
	</td>
</tr>
<tr>
	<td style="font-size:10pt">
		Participants:
	</td>
	<td>
		<xsl:value-of select="/result/statistic/content/participants"/>
	</td>
</tr>
<tr>
	<td align="left">
		Papers:
	</td>
	<td>
		<xsl:value-of select="/result/statistic/content/papers"/>
	</td>
</tr>
<tr>
	<td align="left">
		Review Reports:
	</td>
	<td>
		<xsl:value-of select="/result/statistic/content/reports"/>
	</td>
</tr>
</table>
</xsl:template>







<xsl:template match="/result/criteria/content">
<xsl:apply-templates select = "/result/criteria/status"/><br/>
<table class="chair">
<xsl:if test="/result/criteria/content/criterion">
<tr>
	<th>
		Name
	</th>
	<th>
		Description
	</th>
	<th>
		Max Value
	</th>
	<th>
		Quality Ranking
	</th>
	<th colspan="2">
		options
	</th>
</tr>
</xsl:if>
<xsl:for-each select="/result/criteria/content/criterion">
<tr>
	<td>
		<xsl:value-of select="name"/>
	</td>
	<td>
		<xsl:value-of select="description"/>
	</td>
	<td>
		<xsl:value-of select="maxValue"/>
	</td>
	<td>
		<xsl:value-of select="qualityRating"/>
	</td>
	<td>
		<a>
			<xsl:attribute name = "href">Chair?action=criterion&amp;target=criteria&amp;criterion_target=delete&amp;
			id=<xsl:value-of select="id"/>
			</xsl:attribute>delete
		</a>
	</td>
	<td>
		<a>
			<xsl:attribute name = "href">Chair?action=criterion&amp;target=criteria&amp;criterion_target=change&amp;
			id=<xsl:value-of select="id"/>
			</xsl:attribute>change
		</a>
	</td>
	
</tr>
</xsl:for-each>
</table>
<form method="post" action="Chair?action=criterion&amp;target=criteria&amp;criterion_target=add">
<input type="submit" value="add criterion" class="submit-button"/>
</form>
</xsl:template>

<xsl:template match="/result/criterion_add/content">
<xsl:apply-templates select = "/result/criterion_add/status"/>
<form method="post" action="Chair?action=criterion&amp;target=criteria&amp;criterion_target=save">
<table class="chair">
<tr>
	<td>
		*Name:
	</td>
	<td>
		<input type="text" size="30" maxlength="30" name="criterion_name" id="criterion_name">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/criterion_add/content/name"/>
			</xsl:attribute></input>
	</td>
</tr>
<tr>
	<td>
		*Description:
	</td>
	<td>
		<input type="text" size="50" maxlength="50" name="criterion_description" id="criterion_description">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/criterion_add/content/description"/>
			</xsl:attribute></input>
	</td>
</tr>
<tr>
	<td>
		*Max Value:
	</td>
	<td>
		<input type="text" size="2" maxlength="2" name="criterion_value" id="criterion_value">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/criterion_add/content/value"/>
			</xsl:attribute></input>
	</td>
</tr>
<tr>
	<td>
		*quality Ranking:
	</td>
	<td>
		<input type="text" size="2" maxlength="2" name="criterion_ranking" id="criterion_ranking">
			<xsl:attribute name = "value"><xsl:apply-templates select="/result/criterion_add/content/ranking"/>
			</xsl:attribute></input>
	</td>
</tr>	
</table>
<input type="submit" value="save" class="submit-button"/>
</form>
</xsl:template>

<xsl:template match="/result/show_topics/content">
<xsl:apply-templates select = "/result/show_topics/status"/>
<table class="chair">
<tr>
	<th> Name
	</th>
	<th>
		number of papers
	</th>
</tr>
<xsl:for-each select="/result/show_topics/content/topics">
<tr>
	<td>
		<xsl:value-of select="topic/name"/>
	</td>
	<td>
		<xsl:value-of select="number_of_papers"/>
	</td>
</tr>
</xsl:for-each>
</table>
</xsl:template>


<xsl:template match="/result/show_criterions/content">
<xsl:apply-templates select = "/result/show_criterions/status"/>
<table class="chair" cellpadding="5">
<tr>
	<th> Name
	</th>
	<th>
		Description
	</th>
	<th>
		Max Value
	</th>
	<th>
		Quality Rating
	</th>
</tr>
<xsl:for-each select="/result/show_criterions/content/criterion">
<tr>
	<td>
		<xsl:value-of select="name"/>
	</td>
	<td>
		<xsl:value-of select="description"/>
	</td>
	<td>
		<xsl:value-of select="maxValue"/>
	</td>
	<td>
		<xsl:value-of select="qualityRating"/>
	</td>
</tr>
</xsl:for-each>
</table>
</xsl:template>


<xsl:template match="/result/invite/content">
<xsl:apply-templates select = "/result/invite/status"/>
<xsl:apply-templates select = "/result/invitation_send/status"/>
	<div class="formular">
		<form action="Chair?action=send_invitation" method="post">
		<table style="color:black">
			<tr>
				<td>
					<label for="first name">* first name: </label>
				</td>
				<td>
					<input type="text" id="first_name" name="first_name" size="30" maxlength="30">
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
					<input type="text" id="last_name" name="last_name" size="30" maxlength="30">
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
					<textarea name="comment" rows="3" cols="30">&#160;</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					invite as:
					<input type="radio" name="invite as" value="author" checked=""/>Author
					<input type="radio" name="invite as" value="reviewer"/>Reviewer
					<input type="radio" name="invite as" value="participant"/>Participant
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
<xsl:apply-templates select = "/result/showauthors/status"/>
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
			<td><a><xsl:attribute name = "href">Chair?action=show_authors&amp;delete=true&amp;id=<xsl:value-of select="id"/></xsl:attribute>delete
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
<xsl:apply-templates select = "/result/showauthors_data/status"/>
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
								Title:		
							</td>
							<td width="200">
								<a>
									<xsl:attribute name = "href">papers/<xsl:value-of select="filename"/><!--.<xsl:value-of select="mim_type"/>-->
									</xsl:attribute>
								<xsl:value-of select="title"/>
								</a>
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
<xsl:apply-templates select = "/result/showreviewers/status"/>
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
			<td><a><xsl:attribute name = "href">Chair?action=show_reviewers&amp;delete=true&amp;id=<xsl:value-of select="id"/></xsl:attribute>delete
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
<xsl:apply-templates select = "/result/showreviewers_data/status"/>
	<table class="chair" cellpadding="5">
		<thead>
			<tr align="center">
				<th colspan="2">personell data</th>
				<th colspan="2">ReviewReports</th>
			</tr>
		</thead>
	<xsl:for-each select="/result/showreviewers_data/content/person">
		<tr>
			<td>title: 
			</td>
			<td><xsl:value-of select="title"/>
			</td>
			<xsl:if test="//showreviewers_data/content/ReviewReport">
			<td colspan="2" rowspan="9">
				<table class="chair">
					<xsl:for-each select="/result/showreviewers_data/content/ReviewReport">
						<tr>
							<td>
								Paper:		
							</td>
							<td width="200">
								<a>
									<xsl:attribute name = "href">papers/<xsl:value-of select="paper/filename"/><!--.<xsl:value-of select="paper/mim_type"/>-->
									</xsl:attribute><xsl:value-of select="paper/title"/>
								<xsl:value-of select="title"/>
								</a>
							</td>
						</tr>
						<tr>
							<td>
								Summary:
							</td>
							<td width="100">
								<xsl:value-of select="summary"/>
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


<xsl:template match="/result/show_papers/content">
<xsl:apply-templates select = "/result/show_papers/status"/>
<table class="chair" cellpadding="2">
<thead>
<tr align="center">
	<!--<td>ID</td><td>Conference ID</td><td>Author ID</td>-->
	<th>Title</th>
	<th>Author</th>
	<th>State</th>
	<th colspan="2">options</th>
</tr>
</thead>
<xsl:for-each select="/result/show_papers/content/paperPlus">
<tr>
	<td>
		<a>
			<xsl:attribute name = "href">papers/<xsl:value-of select="paper/filename"/>.<xsl:value-of select="paper/mim_type"/>
			</xsl:attribute>
			<xsl:value-of select="paper/title"/>
			</a>
	</td>
	<td><xsl:value-of select="paper/Person/first_name"/>&#160;<xsl:value-of select="paper/Person/last_name"/></td>
   <td>
   	<xsl:value-of select="paper/state"/>
   </td>
  <!-- <td>
   	<xsl:value-of select="avg"/>
   </td>-->
   <td>
   	<a>
		<xsl:attribute name = "href">Chair?action=assign&amp;id=<xsl:value-of select="paper/id"/></xsl:attribute>
		statistic and assignment
		</a>
   </td>
</tr>
</xsl:for-each>
</table>
</xsl:template>




<xsl:template match="/result/assign/content">
<xsl:apply-templates select = "/result/assign/status"/>
<h5 align="left" style="color:black"><u>Paper:</u></h5>
<table class="chair" cellpadding="5">
	<thead>
		<tr align="center">
			<th>Title</th>
			<th>Author</th>
			<th>state</th>
			<th></th>
		</tr>
	</thead>
	<tr align="center">
		<td>
			<xsl:value-of select="/result/assign/content/paper/title"/>
		</td>
		<td>
			<xsl:value-of select="/result/assign/content/paper/Person/last_name"/>
		</td>
   		<td>
   			<xsl:value-of select="/result/assign/content/paper/state"/>
   		</td>
	</tr>
</table>
<h5 align="left" style="color:black"><u>edited ReviewReports</u></h5>
<table class="chair" cellpadding="5">
	<thead>
		<tr align="center">
			<th>Reviewer</th>
			<th>Summary</th>
		</tr>
	</thead>
	<xsl:for-each select="/result/assign/content/ReviewReport">	
	<tr align="center">
		<td>
			<xsl:value-of select="Person/last_name"/>
		</td>
		<td>
			<xsl:value-of select="summary"/>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<hr/>
		</td>
	</tr>
	<tr>
		<td>
		</td>
		<th>
			Criterion
		</th>
		<th>
			Grade
		</th>
		</tr>
	
	<xsl:for-each select="rating">
	<tr>
		<td>
		</td>
		<td>
			<xsl:value-of select="criterion/name"/>
		</td>
		<td>
			<xsl:value-of select="grade"/>
		</td>
	</tr>
	</xsl:for-each>
	<tr>
		<td colspan="3">
			<hr/>
		</td>
	</tr>		
	
	
	
	
	</xsl:for-each>
</table>


<form method="POST" action="Chair?action=doAssign">	
	<table style="color:black;text-align:center;font-size:12pt" cellpadding="5">
		<thead>
			<tr align="center">
				<th>
					first name
				</th>
				<th>
					last name
				</th>
				<th>
					email
				</th>
				<th>
					Assign
				</th>
				<th>
					number of Reports
				</th>
			</tr>
		</thead>
		<h5 align="left" style="color:black"><u>Reviewers:</u> 
		<xsl:if test="//more">
			(you have to assign <xsl:value-of select="//more"/> more reviewer(s)) 
		</xsl:if>
		<xsl:if test="//even">
			(you have assigned the minimum of reviewers) 
		</xsl:if>
		<xsl:if test="//already">
			(you have assigned  <xsl:value-of select="//already"/> reviewer over the minimum)
		</xsl:if>
		</h5>
	<xsl:for-each select="/result/assign/content/personplus">
		<tr>
			<td><xsl:value-of select="person/first_name"/></td>
			<td><xsl:value-of select="person/last_name"/></td>
			<td><xsl:value-of select="person/email"/></td>	
			<td>
	
			<input type="checkbox" name="CB"><xsl:attribute name = "value"><xsl:value-of select="person/id"/></xsl:attribute>
			<xsl:if test="checked">
			<xsl:attribute name = "checked"/>
			</xsl:if>
			</input>		
</td>
<td>
	<xsl:value-of select="number_of_ReviewReports"/>
</td>
		
	   </tr>
	</xsl:for-each>
	</table>
	<p style="color:black">	<input type="submit" value="submit"/></p>
		</form>
</xsl:template>


<!-- EMAIL-Darstellung-->
<xsl:template match="/result/email/content">
<xsl:apply-templates select = "/result/email/status"/>
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
		<textarea rows="7" name="Cont" id="Cont" cols="28">&#160;
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

<xsl:template match="/result/program/content">
<xsl:apply-templates select = "/result/program/status"/>
<h4 align="left" style="color:black">Paper</h4>
<form method="POST" action="Chair?action=programCommit">	
<table class="chair" cellpadding="5">
	<thead>
		<tr align="center">
			<!--<td>ID</td><td>Conference ID</td><td>Author ID</td>-->
			<th><a href="Chair?action=program&amp;sort=0">Title</a>
			</th>
			<th><a href="Chair?action=program&amp;sort=1">Author</a></th>
			<th><a href="Chair?action=program&amp;sort=2">Grade</a></th>
			<th><a href="Chair?action=program&amp;sort=3">Topic</a></th>
			<th>Choice</th>
		</tr>
	</thead>
		<xsl:for-each select="/result/program/content/FINISH">
	<tr align="center">
		<td>
			<xsl:value-of select="title"/>
		</td>
		<td>
	<xsl:value-of select="author"/>
		</td>
				<td>
					<xsl:value-of select="avggrade"/>
		</td>
				<td>
	<xsl:value-of select="topic"/>
		</td>

<td>
	<input type="checkbox" name="CB"><xsl:attribute name = "value"><xsl:value-of select="paper_id"/></xsl:attribute>
			<xsl:if test="state ='3'">
			<xsl:attribute name = "checked"/>
			</xsl:if>
			</input>	
			</td>
	  </tr>
		</xsl:for-each>
</table>
<p>	<input type="submit" value="Submit Changes"/></p>
		</form>
		<form method="POST" action="Chair?action=programShow">	
		<p>	<input type="submit" value="Program Preview"/></p>
		</form>
	<form method="POST" action="Chair?action=programShow&amp;finish=true">	
		<p>	<input type="submit" value="Finish Conference and Create Program"/></p>
</form>
</xsl:template>



<xsl:template match="/result/proshow/content">
<xsl:apply-templates select = "/result/proshow/status"/>
<table class="chair" cellpadding="5">
<xsl:for-each select="/result/proshow/content/day">
	<tr align="center">
		<td colspan="3">
		DAY
			<xsl:value-of select="@date"/>
		</td>
	</tr>
	<tr align="center">
		<th>Time</th>
		<th>Title</th>
		<th>Author</th>
	</tr>
	<xsl:for-each select="FINISH">
	<tr>
		<td>
			<xsl:value-of select="time"/>
		</td>
		<td>
			<xsl:value-of select="title"/>
		</td>
		<td>
			<xsl:value-of select="author"/>
		</td>
	</tr>
	</xsl:for-each>
</xsl:for-each>
</table>
</xsl:template>







<xsl:template match="/result/login/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/show_criterions/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/invite/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/save_initial/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/criteria/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/setup_topics/status">
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

<xsl:template match="/result/show_topics/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/setup/status">
<xsl:value-of select='.'/>
</xsl:template>

<xsl:template match="/result/email/status">
<xsl:value-of select='.'/><br></br> 
</xsl:template>


</xsl:stylesheet>