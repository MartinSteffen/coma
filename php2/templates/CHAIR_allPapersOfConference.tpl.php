<? 
include("header.tpl.php");
$input = d('chair');
$confID = $input['confID'];
$confName = $input['confName'];
$papers = $input['papers'];
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top"><span class="textBold">List of all papers in 
      conference :</span> <span class="text"> 
      <? echo $confName ?>
      </span></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <? foreach($papers as $paper) 
  { ?>
  <tr align="left" valign="top"> 
    <td width="125" class="textBold">Paper name</td>
    <td width="100%" class="text"><a href="index.php?m=chair&a=papers&s=paper&paperID=<? echo $paper['paperID'] ?>" class="normal"><? echo $paper['paperName']; ?></a></td>
  </tr>
  <tr align="left" valign="top"> 
    <td class="textBold">Author</td>
    <td class="text"><? echo $paper['authorName']; ?></td>
  </tr>
  <tr align="left" valign="top"> 
    <td class="textBold">Description</td>
    <td class="text"><? echo $paper['paperDesc']; ?></td>
  </tr>
  <tr align="left" valign="top"> 
    <td class="textBold">&nbsp;</td>
    <td class="text">&nbsp;</td>
  </tr>
  <tr> 
    <td height="1"><img height="1" width="125" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
  <? } ?>
</table>
<?
include("footer.tpl.php");
?>
