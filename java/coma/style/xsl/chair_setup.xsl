<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" indent="yes"  doctype-public= "-//W3C//DTD XHTML 1.1//EN" 
doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="iso-8859-1"/>


<!-- overview about conference-->

<xsl:template match="/result/setup/content" name="setup">
<xsl:if test="//setup//content">
<table class="chair" cellpadding="2">
<form action="Chair?action=send_setup&amp;step=update" method="post">
<tr>
	<td>
	Name:
	</td>
	<td width="200"> 
		<xsl:apply-templates select="/result/setup/content/Conference/name"/>
	</td>
	<td>
		<input type="text" id="conference name" name="conference name" size="30" maxlength="30">
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/Conference/name"/>
  			</xsl:attribute>
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
			<xsl:attribute name="value">
  				<xsl:apply-templates select="/result/setup/content/Conference/homepage"/>
  			</xsl:attribute>
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
		<textarea id="description" name="description" rows="3" cols="30" class="textarea" wrap="physical">
			<xsl:apply-templates select="/result/setup/content/Conference/description"/>
  		</textarea>
	</td>
</tr>
<tr>
	<td>
	min reviewer per paper::
	</td>
	<td> 
		<xsl:apply-templates select="/result/setup/content/Conference/min"/>
	</td>
	<td>
		you can't change this
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
		<!--
		<input type="text" id="start_day" name="start_day" size="2" maxlength="2">
  		</input>:
		<input type="text" id="start_month" name="start_month" size="2" maxlength="2">
  		</input>:
		<input type="text" id="start_year" name="start_year" size="4" maxlength="4">
  		</input>-->
  		you can't change this
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
		<!--<input type="text" id="end_day" name="end_day" size="2" maxlength="2">
  		</input>:
		<input type="text" id="end_month" name="end_month" size="2" maxlength="2">
  		</input>:
		<input type="text" id="end_year" name="end_year" size="4" maxlength="4">
  		</input>-->
  		you can't change this
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
</xsl:if>
</xsl:template>




<!-- Setup a new conference : Step 1-->

<xsl:template match="/result/setup_new_step1/content" name="setup_step1">
<xsl:if test="//setup_new_step1//content">
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
</xsl:if>
</xsl:template>

<!-- Setup a new conference : Step 2 -->

<xsl:template match="/result/setup_new_step2/content" name="setup_step2">
<xsl:if test="//setup_new_step2//content">
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
</xsl:if>
</xsl:template>

</xsl:stylesheet>