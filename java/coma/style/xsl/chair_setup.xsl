<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" indent="yes"  doctype-public= "-//W3C//DTD XHTML 1.1//EN" 
doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="iso-8859-1"/>


<!-- overview about conference-->

<xsl:template match="/result/setup/content" name="setup_conference">
<xsl:if test="//setup//content">
<xsl:apply-templates select = "/result/setup/status"/>
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
		<xsl:apply-templates select="/result/setup/content/conference/name"/>
	</td>
	<td>
		<input type="text" id="conference name" name="conference name" size="30" maxlength="30">
			<!--<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/conference/name"/>
  			</xsl:attribute>-->
  		</input>
	</td>
</tr>
<tr>
	<td>
	Homepage: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/conference/homepage"/>
	</td>
	<td>
		<input type="text" id="homepage" name="homepage" size="30" maxlength="30">
			<!--<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/conference/homepage"/>
  			</xsl:attribute>-->
  		</input>
	</td>
</tr>
<tr>
	<td>
	Description: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/conference/description"/>
	</td>
	<td>
		<input type="text" id="description" name="description" size="50" maxlength="50">
			<!--<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/conference/description"/>
  			</xsl:attribute>-->
  		</input>
	</td>
</tr>
<!--<tr>
	<td>
	Description:
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/conference/description"/>
	</td>
	<td>
		<textarea id="description" name="description" rows="3" cols="30" class="textarea" wrap="physical">&#160;
			<xsl:apply-templates select="/result/setup/content/conference/description"/>
  		</textarea>
	</td>
</tr>-->
<tr>
	<td>
	min reviewer(s) per paper:
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/conference/min"/>
	</td>
	<td>
		<xsl:choose>
			<xsl:when test="/result/setup/content/min_setup">
				<input type="text" id="min" name="min" size="2" maxlength="2"/>
			</xsl:when>
			<xsl:otherwise>	
				you can't change this
			</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr>
	<td>
	Start:
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/conference/start"/>
	</td>
	<td>
		<xsl:choose>
			<xsl:when test="/result/setup/content/start_setup">
				<input type="text" id="start_day" name="start_day" size="2" maxlength="2">
  				</input>:
				<input type="text" id="start_month" name="start_month" size="2" maxlength="2">
  				</input>:
				<input type="text" id="start_year" name="start_year" size="4" maxlength="4">
  				</input>
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
		<xsl:apply-templates select="/result/setup/content/conference/end"/>
	</td>
	<td>
		<xsl:choose>
			<xsl:when test="/result/setup/content/end_setup">
				<input type="text" id="end_day" name="end_day" size="2" maxlength="2">
  				</input>:
				<input type="text" id="end_month" name="end_month" size="2" maxlength="2">
  				</input>:
				<input type="text" id="end_year" name="end_year" size="4" maxlength="4">
  				</input>
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
		 <xsl:apply-templates select="/result/setup/content/conference/notification"/>
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
	Abstract Submission: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/conference//abstract"/>
	</td>
	<td>
		<xsl:choose>
			<xsl:when test="/result/setup/content/abstract_deadline">
				<input type="text" id="abstract_day" name="abstract_day" size="2" maxlength="2">
  				</input>:
				<input type="text" id="abstract_month" name="abstract_month" size="2" maxlength="2">
  				</input>:
				<input type="text" id="abstract_year" name="abstract_year" size="4" maxlength="4">
  				</input>
			</xsl:when>
			<xsl:otherwise>	
				deadline terminated
			</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr>
	<td>
	Paper Submission: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/conference//paper"/>
	</td>
	<td>
		<xsl:choose>
			<xsl:when test="/result/setup/content/paper_deadline">
				<input type="text" id="paper_day" name="paper_day" size="2" maxlength="2">
  				</input>:
				<input type="text" id="paper_month" name="paper_month" size="2" maxlength="2">
  				</input>:
				<input type="text" id="paper_year" name="paper_year" size="4" maxlength="4">
  				</input>
			</xsl:when>
			<xsl:otherwise>	
				deadline terminated
			</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr>
	<td>
	Review: 
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/conference//review"/>
	</td>
	<td>
		<xsl:choose>
			<xsl:when test="/result/setup/content/review_deadline">
				<input type="text" id="review_day" name="review_day" size="2" maxlength="2">
  				</input>:
				<input type="text" id="review_month" name="review_month" size="2" maxlength="2">
  				</input>:
				<input type="text" id="review_year" name="review_year" size="4" maxlength="4">
  				</input>
			</xsl:when>
			<xsl:otherwise>	
				deadline terminated
			</xsl:otherwise>
		</xsl:choose>
	</td>
</tr>
<tr>
	<td>
	Final Submission:
	</td>
	<td> 
		 <xsl:apply-templates select="/result/setup/content/conference//final"/>
	</td>
	<td>
		<xsl:choose>
			<xsl:when test="/result/setup/content/final_deadline">
				<input type="text" id="final_day" name="final_day" size="2" maxlength="2">
  				</input>:
				<input type="text" id="final_month" name="final_month" size="2" maxlength="2">
  				</input>:
				<input type="text" id="final_year" name="final_year" size="4" maxlength="4">
  				</input>
			</xsl:when>
			<xsl:otherwise>	
				deadline terminated
			</xsl:otherwise>
		</xsl:choose>
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
<xsl:apply-templates select = "/result/setup_topics/status"/>
<table class="chair">
	<tr>
		<th>Topic name
		</th>
		<th colspan="2">
			options
		</th>
	</tr>
	<xsl:for-each select="result/setup_topics/content/topics">
		<tr>
			<td>
				<xsl:value-of select="topic/name"/>
			</td>
			<form method="post">
				<xsl:attribute name="action">Chair?action=topic&amp;topic_target=delete&amp;target=topics&amp;id=<xsl:value-of select="topic/id"/>
				</xsl:attribute>
			<td>
				<input type="submit" value="delete" class="submit-button"/> 
			</td>
			</form>
			<!--<form method="post">
				<xsl:attribute name="action">Chair?action=topic&amp;topic_target=update&amp;target=topics&amp;id=<xsl:value-of select="id"/>
  				</xsl:attribute>
			<td>
				<input type="text" size="30" maxlength="30" name="new_name"/>	
			</td>
			<td>
				<input type="submit" value="update"/> 
			</td>
			</form>-->		
		</tr>
	</xsl:for-each>
	<tr>
		<td>
		-----------------------------------------------
		</td>
	</tr>
	<tr>
		<td>
			<form action="Chair?action=topic&amp;topic_target=add" method="post">add
				<input type="text" size="3" maxlength="3" name="topics"/> new topic(s)
				<input type="submit" value="GO" class="submit-button"/>
			</form>
		</td>
	</tr>
</table>
</xsl:if>
</xsl:template>

<xsl:template match="/result/add_topics/content" name="add_topics">
<xsl:if test="//add_topics//content">
<xsl:apply-templates select = "/result/add_topics/status"/>
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

<xsl:template match="/result/save_initial/content" name="save_initial">
<xsl:if test="//save_initial//content">
	<xsl:choose>
		<xsl:when test="//error">
			<xsl:for-each select="/result/save_initial/content/error">
				<xsl:value-of select='.'/>
			</xsl:for-each> 
		</xsl:when>
		<xsl:otherwise>
		<p align="center">
			Save the initial setup<br/>
			you can't change the number of topics and the criteria<br/>
			if you sure, please choose a name for the conference
		</p>
		<form action="Chair?action=setup&amp;target=save_initial" method="post"> 
			<input type="text" size="30" maxlength="30" name="name" id="name"/>
			<input type="submit" value="Save" class="submit-button"/>
		</form>
		</xsl:otherwise>
	</xsl:choose>
</xsl:if>
</xsl:template>

<xsl:template match="/result/criteria/content" name="criteria">
</xsl:template>

</xsl:stylesheet>

