<?
	include("header.tpl.php");
	$forumlist = $TPL['Forumliste'];
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <? if (!count($forumlist)==0)
     { ?>
    	<td class="textBold">Please select a forum :</td>
  <? }
     else
	 { ?>
	 	<td class="textBold">There are no forums you can choose.</td>
  <? } ?>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <? foreach($forumlist as $forum)
 {  ?>
  <tr>
    <td width="80" class="textBold">Name</td>
    <td width="100%"><a href="index.php?m=forum&a=viewforum&forumID=<? echo $forum[0] ?>" class="normal">
      <? echo $forum[2] ?>
      </a></td>
  </tr>
  <tr>
    <td width="80" class="textBold">Type</td>
    <td class="text">
      <?
      	switch ($forum[3])
      	{
      		case 1:	echo "open"; break;
      		case 2: echo "comitee only"; break;
      		case 3: echo "paperdiscussion"; break;
      	}
      ?>
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
