<? 
include("header.tpl.php");
$input = d('chair');
$paper = $input['paper'];
$report = $input['report'];
$reviewers = $report['reviewers'];
?>
<link rel="stylesheet" href="style.css" type="text/css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="textBold">Manage a paper</td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">Paper name</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $paper['paperName'] ?>
    </td>
    <td width="257" class="text" align="left" valign="top">&nbsp; </td>
  </tr>
  <tr> 
    <td class="textBold" align="left" valign="top" width="117">Author</td>
    <td class="text" align="left" valign="top"> <a href="index.php?m=chair&a=users&s=user&userID=<? echo $paper['authorID'] ?>" class="normal"> 
      <? echo $paper['authorName'] ?>
      </a> </td>
    <td class="text" align="left" valign="top" width="257"><a href="index.php?m=chair&a=papers&s=allPapersOfUser&userID=<? echo $paper['authorID'] ?>" class="normal">List 
      all papers of the author</a></td>
  </tr>
  <tr> 
    <td class="textBold" align="left" valign="top" width="117">In conference</td>
    <td class="text" align="left" valign="top"> <a href="index.php?m=chair&a=conferences&s=conference&confID=<? echo $paper['confID'] ?>" class="normal"> 
      <? echo $paper['confName'] ?>
      </a> </td>
    <td class="text" align="left" valign="top" width="257"><a href="index.php?m=chair&a=papers&s=allPapersOfConference&confID=<? echo $paper['confID'] ?>" class="normal">List 
      all papers in the conference</a></td>
  </tr>
  <tr> 
    <td class="textBold" align="left" valign="top" width="117">Description</td>
    <td class="text" align="left" valign="top"> 
      <? echo $paper['paperDesc'] ?>
    </td>
    <td class="text" align="left" valign="top" width="257">&nbsp; </td>
  </tr> 
  <tr> 
    <td height="1" width="117"><img height="1" width="117" src="/templates/images/spacer.gif"></td>
    <td></td>
    <td width="257"><img height="1" width="257" src="/templates/images/spacer.gif"></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="left" valign="top"> 
    <td width="116" class="textBold">State</td>
    <td width="204" class="<? echo $paper['class']; ?>"> 
      <? echo $paper['state']; ?>
    </td>
    <td width="100%">&nbsp;</td>
  </tr>
  <tr align="left" valign="top"> 
    <td width="116" class="textBold">Last edited</td>
    <td width="204" class="text"> 
      <? echo $paper['lastEdited'] ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr align="left" valign="top"> 
    <td width="116" class="textBold">Version</td>
    <td width="204" class="text"> 
      <? echo $paper['version'] ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr align="left" valign="top"> 
    <td width="116" class="textBold">Total Grade</td>
    <td width="204" class="<? echo $report['totalGradeColor'] ?>"> 
      <? echo $report['totalGrade'] ?>
    </td>
    <td>
	  <? if ($report['isReviewed'])
	    {
		?>
		<form name="form1" method="post" action="index.php?m=chair&a=papers&s=updateState">
		<input type="submit" name="accept" value="Accept">
        <input type="submit" name="reject" value="Reject" onclick="return confirm('Are you sure you want to reject the paper?')">
		<input type="submit" name="reset" value="Reset">		
		<input type="hidden" name="paperID" value="<? echo $paper['paperID'] ?>">
		</form>
	  <? } ?>
	</td>
  </tr>  
  <tr> 
    <td height="1"><img height="1" width="116" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="204" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="middle"><img src="templates/images/arrow.gif" width="30" height="17"><a href="index.php?m=chair&a=papers&s=paperReviewerAuto&paperID=<?echo $paper['paperID'] ?>" class="menus">Send 
      the paper to reviewers automatically</a></td>
  </tr>
  <tr> 
    <td align="left" valign="middle"><img src="templates/images/arrow.gif" width="30" height="17"><a href="index.php?m=chair&a=papers&s=paperReviewerManual&paperID=<? echo $paper['paperID'] ?>" class="menus">Send 
      the paper to reviewers manually</a></td>
  </tr>
  <tr> 
    <td align="left" valign="middle">&nbsp;</td>
  </tr>
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
          <td width="100%" bgcolor="#CCCCCC"><a href="index.php?m=chair&a=papers&s=removeReviewer&reviewerID=<? echo $reviewer['reviewerID'] ?>" class="normal" onclick="return confirm('Are you sure you want to remove the reviewer?')">Remove 
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
