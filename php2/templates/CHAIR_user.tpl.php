<? 
include("header.tpl.php");
$input = d('chair');
$person = $input['person'];
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="textBold">Manage a user</td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">Title</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $person['title'] ?>
    </td>
  </tr>
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">First name</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $person['first_name'] ?>
    </td>
  </tr>
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">Last name</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $person['last_name'] ?>
    </td>
  </tr>
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">Email</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <a href="mailto:<? echo $person['email'] ?>" class="normal"><? echo $person['email'] ?></a>
    </td>
  </tr>  
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">Street</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $person['street'] ?>
    </td>
  </tr>
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">City</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $person['city'] ?>
    </td>
  </tr>
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">Postal Code</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $person['postal_code'] ?>
    </td>
  </tr>
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">State</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $person['state'] ?>
    </td>
  </tr>
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">Country</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $person['country'] ?>
    </td>
  </tr>  
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">Phone</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $person['phone_number'] ?>
    </td>
  </tr>
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">Fax</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $person['fax_number'] ?>
    </td>
  </tr>                
  <tr> 
    <td height="1" width="117"><img height="1" width="117" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<? if ((!($paper['stateID'] == 3)) && (!($paper['stateID'] == 4)))
   {  ?>
  <tr> 
    <td align="left" valign="middle"><img src="templates/images/arrow.gif" width="30" height="17"><a href="index.php?m=chair&a=papers&s=allPapersOfUser&userID=<?echo $person['personID'] ?>" class="menus">View all papers of the user</a></td>
  </tr>
  <tr> 
    <td align="left" valign="middle">&nbsp;</td>
  </tr>
<?  }  ?>  
  <tr>
<?
if (!count($reviewers)==0)  
{
?>
    <td align="left" valign="middle" class="textBold">Reports from the reviewers:</td>
<?
}
else
{ 
?>
    <td align="left" valign="middle" class="textBold">There are no reviewers for this paper.</td>
<?
}
?>
  </tr>
</table>
<br>
<?
$count = 0;
foreach ($reviewers as $reviewer)
{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="100%"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="86" class="textBold" bgcolor="#CCCCCC">Reviewer</td>
          <td width="274" class="text" bgcolor="#CCCCCC"><? echo $reviewer['reviewerName'] ?></td>
          <td width="100%" bgcolor="#CCCCCC"><a href="index.php?m=chair&a=papers&s=removeReviewer&reviewerID=<? echo $reviewer['reviewerID'] ?>&paperID=<? echo $paper['paperID'] ?>" class="normal" onclick="return confirm('Are you sure you want to remove the reviewer?')">Remove 
            this reviewer</a></td>
        </tr>
        <tr> 
          <td height="1"><img height="1" width="86" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="274" src="/templates/images/spacer.gif"></td>
          <td></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="40">&nbsp;</td>
          <td width="90" class="textBold">Total grade</td>
          <td width="100%" class="<? echo $reviewer['totalGradeColor'] ?>"><? echo $reviewer['totalGrade'] ?></td>
        </tr>	  
        <tr> 
          <td width="40">&nbsp;</td>
          <td width="90" class="textBold">Summary</td>
          <td width="100%" class="text"><? echo $reviewer['summary'] ?></td>
        </tr>
        <tr> 
          <td width="40">&nbsp;</td>
          <td width="90" class="textBold">Remarks</td>
          <td width="100%" class="text"><? echo $reviewer['remarks'] ?></td>
        </tr>
        <tr> 
          <td width="40">&nbsp;</td>
          <td width="90" class="textBold">Confidential</td>
          <td width="100%" class="text"><? echo $reviewer['confidential'] ?></td>
        </tr>
        <tr> 
          <td width="40">&nbsp;</td>
          <td width="90" class="textBold">&nbsp;</td>
          <td width="100%" class="text">
            <input type="submit" name="dummySubmit" value="Show / Hide details" onClick="toggle('<? echo "hide".$count ?>');">
          </td>
        </tr>		
        <tr> 
          <td height="1"><img height="1" width="40" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="90" src="/templates/images/spacer.gif"></td>
          <td></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
	  <div id='<? echo "hide".$count ?>'>	
	<?
	$ratings = $reviewer['ratings'];
	foreach ($ratings as $rating)
	{
	?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="86">&nbsp;</td>
          <td width="86" class="textBold">Criterion</td>
          <td width="100%" class="text"><? echo $rating['criterionName'] ?></td>
        </tr>
        <tr> 
          <td width="86">&nbsp;</td>
          <td width="86" class="textBold">Grade</td>
          <td width="100%" class="text"><? echo $rating['grade']."%" ?></td>
        </tr>
        <tr> 
          <td width="86">&nbsp;</td>
          <td width="86" class="textBold">Ratio</td>
          <td width="100%" class="text"><? echo $rating['qualityRating']."%" ?></td>
        </tr>
        <tr> 
          <td width="86">&nbsp;</td>
          <td width="86" class="textBold">Comment</td>
          <td width="100%" class="text"><? echo $rating['comment'] ?></td>
        </tr>
        <tr> 
          <td width="86">&nbsp;</td>
          <td width="86">&nbsp;</td>
          <td width="100%">&nbsp;</td>
        </tr>
        <tr> 
          <td height="1"><img height="1" width="86" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="86" src="/templates/images/spacer.gif"></td>
          <td></td>
        </tr>
      </table>
	  <?
	  }
	  ?>
	  </div>	  
    </td>
  </tr>
  <tr>
	<td>&nbsp;</td>  
  </tr>
</table>
<script>
	toggle('<? echo "hide".$count ?>');
</script>
<?
$count++;
}
?>
<br>
<br>
<?
include("footer.tpl.php");
?>
