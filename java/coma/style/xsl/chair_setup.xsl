<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" indent="yes"  doctype-public= "-//W3C//DTD XHTML 1.1//EN" 
doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="iso-8859-1"/>


<!-- overview about conference-->

<xsl:template match="/result/setup/content" name="setup_conference">
<xsl:if test="//setup//content">
<table class="chair" cellpadding="2">
<form action="Chair?action=send_setup&amp;target=conference" method="post">
<tr>
	<td colspan="2">
	<h4 style="color:black">General</h4>
	</td>
</tr>
<tr>
	<td>
	Name:
	</td>
	<td width="200"> 
		<xsl:apply-templates select="/result/setup/content/Conference/name"/>
	</td>
	<td>
		<input type="text" id="conference name" name="conference name" size="30" maxlength="30">
			<!--<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/Conference/name"/>
  			</xsl:attribute>-->
  		</input>
	</td>
</tr>
<tr>
	<td>
	Homepage: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference/homepage"/>
	</td>
	<td>
		<input type="text" id="homepage" name="homepage" size="30" maxlength="30">
			<!--<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/Conference/homepage"/>
  			</xsl:attribute>-->
  		</input>
	</td>
</tr>
<tr>
	<td>
	Description:
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference/description"/>
	</td>
	<td>
		<textarea id="description" name="description" rows="3" cols="30" class="textarea" wrap="physical">&#160;
			<!--<xsl:apply-templates select="/result/setup/content/Conference/description"/>-->
  		</textarea>
	</td>
</tr>
<tr>
	<td>
	min reviewer per paper:
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference/min"/>
	</td>
	<td>
		<xsl:choose>
			<xsl:when test="/result/setup/content/min_setup">
				<input type="text" id="min" name="min" size="2" maxlength="2"/>*
			</xsl:when>
			<xsl:otherwise>	
				you can't change this
			</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr>
	<td>
	number of topics:
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/topic_numbers"/>
	</td>
	<td>
		<!--<xsl:choose>
			<xsl:when test="/result/setup/content/min_setup">
				<input type="text" id="min" name="min" size="2" maxlength="2"/>*
			</xsl:when>
			<xsl:otherwise>	
				you can't change this
			</xsl:otherwise>
		</xsl:choose>-->
		<a href="Chair?action=setup&amp;target=topics">configure topics</a>
	</td>
</tr>
<tr>
	<td>
	Start:
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference/start"/>
	</td>
	<td>
		<xsl:choose>
			<xsl:when test="/result/setup/content/start_setup">
				<input type="text" id="start_day" name="start_day" size="2" maxlength="2">
  				</input>:
				<input type="text" id="start_month" name="start_month" size="2" maxlength="2">
  				</input>:
				<input type="text" id="start_year" name="start_year" size="4" maxlength="4">
  				</input>*
			</xsl:when>
			<xsl:otherwise>	
				you can't change this
			</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr>
	<td>
	End: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference/end"/>
	</td>
	<td>
		<xsl:choose>
			<xsl:when test="/result/setup/content/end_setup">
				<input type="text" id="end_day" name="end_day" size="2" maxlength="2">
  				</input>:
				<input type="text" id="end_month" name="end_month" size="2" maxlength="2">
  				</input>:
				<input type="text" id="end_year" name="end_year" size="4" maxlength="4">
  				</input>*
			</xsl:when>
			<xsl:otherwise>	
				you can't change this
			</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr>
	<td>
	Notification:
	</td>
	<td> 
		 <xsl:apply-templates select="/result/setup/content/Conference/notification"/>
	</td>
	<td>
		<input type="text" id="not_day" name="not_day" size="2" maxlength="2">
  		</input>:
		<input type="text" id="not_month" name="not_month" size="2" maxlength="2">
  		</input>:
		<input type="text" id="not_year" name="not_year" size="4" maxlength="4">
  		</input>
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
		<td>
		<input type="text" id="abstract_day" name="abstract_day" size="2" maxlength="2">
  		</input>:
		<input type="text" id="abstract_month" name="abstract_month" size="2" maxlength="2">
  		</input>:
		<input type="text" id="abstract_year" name="abstract_year" size="4" maxlength="4">
  		</input>
	</td>
</tr>
<tr>
	<td>
	Paper submission: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference//paper"/>
	</td>
		<td>
		<input type="text" id="paper_day" name="paper_day" size="2" maxlength="2">
  		</input>:
		<input type="text" id="paper_month" name="paper_month" size="2" maxlength="2">
  		</input>:
		<input type="text" id="paper_year" name="paper_year" size="4" maxlength="4">
  		</input>
	</td>
</tr>
<tr>
	<td>
	review: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference//review"/>
	</td>
	<td>
		<input type="text" id="review_day" name="review_day" size="2" maxlength="2">
  		</input>:
		<input type="text" id="review_month" name="review_month" size="2" maxlength="2">
  		</input>:
		<input type="text" id="review_year" name="review_year" size="4" maxlength="4">
  		</input>
	</td>
</tr>
<tr>
	<td>
	final submission:
	</td>
	<td> 
		 <xsl:apply-templates select="/result/setup/content/Conference//final"/>
	</td>
	<td>
		<input type="text" id="final_day" name="final_day" size="2" maxlength="2">
  		</input>:
		<input type="text" id="final_month" name="final_month" size="2" maxlength="2">
  		</input>:
		<input type="text" id="final_year" name="final_year" size="4" maxlength="4">
  		</input>
	</td>
</tr>
<tr>
	<td>
	</td>
	<td>
	</td>
	<td>
		<input type="submit" value="update" class="submit-button"/>
	</td>
</tr>
</form>
</table>
</xsl:if>
</xsl:template>

<xsl:template match="/result/setup_topics/content" name="setup_topics">
<xsl:if test="//setup_topics//content">
<table class="chair">
	<xsl:for-each select="result/setup_topics/content/topic">
		<tr>
			<td>
				<xsl:value-of select="name"/>
			</td>	
			<td>
				<a href="Chair?action=topic&amp;target=delete">delete</a>
			</td>
			<!--<td>
				<a href="Chair?action=topic&amp;target=update">update</a>
			</td>-->	
		</tr>
	</xsl:for-each>
	<tr>
		<td>
			<form action="Chair?action=topic&amp;topic_target=add" method="post">add
				<input type="text" size="3" maxlength="3" name="topics"/> new topics
				<input type="submit" value="GO" class="submit-button"/>
			</form>
		</td>
		</tr>
</table>
</xsl:if>
</xsl:template>

<xsl:template match="/result/add_topics/content" name="add_topics">
<xsl:if test="//add_topics//content">
<table class="chair">
	<form action="Chair?action=topic&amp;topic_target=save&amp;target=topics" method="post">
		<xsl:for-each select="result/add_topics/content/topic">
		<tr>
			<td>
				<xsl:value-of select="name"/>
			</td>	
		</tr>
		</xsl:for-each>
		<xsl:for-each select="result/add_topics/content/topic_new">
			<tr>
				<td>
					<input type="text" size="30" maxlength="30">
						<xsl:attribute name="name">
  							<xsl:value-of select="number"/>
  						</xsl:attribute>
  					</input>
				</td>	
			</tr>
		</xsl:for-each>
		<tr>
			<td align="left">
				<input type="submit" value="save"/>
			</td>
		</tr>
	</form>
</table>
</xsl:if>
</xsl:template>

</xsl:stylesheet>

