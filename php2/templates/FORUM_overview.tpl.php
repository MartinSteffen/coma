<?
	include("header.tpl.php");
	$conflist = $TPL['Conf'];
?>
<p><b>Forum&uuml;bersicht</b></p>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <? if (!count($conflist)==0)
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
  <? foreach($conflist as $conferences)
 {  ?>
  <tr>
    <td width="80" class="textBold">Name</td>
    <td width="100%"><a href="index.php?m=forum&a=viewconf&confID=<? echo $conferences[0] ?>" class="normal">
      <? echo $conferences[5] ?>
      </a></td>
  </tr>
  <tr>
    <td width="80" class="textBold">Description</td>
    <td class="text">
      <? echo $conferences[4] ?>
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
