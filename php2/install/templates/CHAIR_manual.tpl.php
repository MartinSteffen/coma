<? 
include("header.tpl.php");
$input = d('chair');
$minimumReviewers = $input['minimum'];
$alreadyRev = $input['alreadyRev'];
$reviewers = $input['reviewers'];
$mustChoose = $input['mustChoose'];
$paperID = $input['paperID'];
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="textBold" width="100%"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td class="textBold" width="350">Send the paper to reviewers manually.</td>
          <td width="100%" align="right"><a href="index.php?m=chair&a=papers&s=paper&paperID=<? echo $paperID ?>" class="normal">Back 
            to paper</a></td>
		  <td></td>
        </tr>
        <tr> 
          <td height="1"><img height="1" width="350" src="/templates/images/spacer.gif"></td>
          <td></td>
		  <td height="1"><img height="1" width="20" src="/templates/images/spacer.gif"></td>
        </tr>		
      </table>
    </td>
    <td></td>
  </tr>
  <tr> 
    <td class="textBold">&nbsp;</td>
    <td></td>
  </tr>
  <tr> 
    <td class="text"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="200" class="textBold">Minimum reviewers required</td>
          <td width="100%" class="text"> 
            <? echo $minimumReviewers ?>
          </td>
        </tr>
        <tr> 
          <td width="200" class="textBold">Already working reviewers</td>
          <td width="100%" class="text"> 
            <? echo $alreadyRev ?>
          </td>
        </tr>
        <tr> 
          <td height="1" width="200"><img height="1" width="200" src="/templates/images/spacer.gif"></td>
          <td width="100%"></td>
        </tr>
      </table>
    </td>
    <td></td>
  </tr>
  <tr> 
    <td class="textBold">&nbsp;</td>
    <td></td>
  </tr>
  <tr> 
    <? if (!count($reviewers)==0)
   {  ?>
    <td class="textBold">Choose reviewers from the list:</td>
    <? }
   else
   {  ?>
    <td class="textBold">There are no reviewers to choose.</td>
    <? }  ?>
  </tr>
  <tr> 
    <td class="textBold">&nbsp;</td>
    <td></td>
  </tr>
  <tr> 
    <td class="text"> 
      <form name="form1" method="post" action="index.php?m=chair&a=papers&s=sendManual">
        <input type="hidden" name="paperID" value="<? echo $paperID ?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <?
 	foreach ($reviewers as $reviewer)
	{
 ?>
          <tr> 
            <td width="39" align="right"> 
              <input type="checkbox" name="reviewers[]" value="<? echo $reviewer['reviewerID'] ?>" id="check">
            </td>
            <td width="100%" class="text"> 
              <? echo $reviewer['reviewerName'] ?>
            </td>
          </tr>
          <?
	}
    if (!count($reviewers)==0)	
	{
?>
          <tr> 
            <td width="39">&nbsp; </td>
            <td width="100%" class="text"> 
              <script>
function checkNumberReviewers(minimum)
{
	number = 0;
	if(document.form1.check.length)
	{
		for (i=0;i<document.form1.check.length;i++)
		{
			if (document.form1.check[i].checked)
			{
				number++;
			}
		}
	}
	else
	{
		if (document.form1.check.checked)
		{	
			number++;
		}
	}
	if (number<minimum)
	{
		alert("You must choose minimum "+minimum+" reviewers");
		return false;
	}
	else
	{
		return true;
	}	
}
</script>
              <input type="submit" name="Submit" value="Send" onClick="return checkNumberReviewers(<? echo $input['mustChoose'] ?>)">
            </td>
          </tr>
          <?  }  ?>
          <tr> 
            <td height="1"><img height="1" width="39" src="/templates/images/spacer.gif"></td>
            <td></td>
          </tr>
        </table>
      </form>
    </td>
    <td></td>
  </tr>
</table>
<br>
<?
include("footer.tpl.php");
?>