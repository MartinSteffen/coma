<? 
include("header.tpl.php");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <? if (!count(d("chair"))==0)
     { ?>
    	<td class="textBold">Please select a conference :</td>
  <? }
     else
	 { ?>
	 	<td class="textBold">There are no conferences you can choose.</td>
  <? } ?>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <? foreach(d("chair") as $conferences)
 {  ?>
  <tr> 
    <td width="80" class="textBold">Name</td>
    <td width="100%"><a href="index.php?m=chair&a=users&s=allUsersOfConference&confID=<? echo $conferences['id'] ?>" class="normal">
      <? echo $conferences['name'] ?>
      </a></td>
  </tr>
  <tr> 
    <td width="80" class="textBold">Description</td>
    <td class="text"> 
      <? echo $conferences['desc'] ?>
    </td>
  </tr>
  <tr> 
    <td width="80">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="1" width="110"><img height="1" width="138" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
  <? } ?>
</table>
<?
include("footer.tpl.php");
?>
