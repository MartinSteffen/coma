<? 
include("header.tpl.php");
$input = d("chair");
$conferences = $input['conferences'];
$user = $input['userID'];
?>
<form name="form1" method="post" action="index.php?m=chair&a=users&s=makeInviteUser">
<input type="hidden" name="userID" value="<? echo $user ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="textBold">Please choose the roles to invite to :</td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" width="60">Chair</td>
    <td align="center" width="75">Reviewer</td>
    <td align="center" width="60">Author</td>
    <td align="center" width="80">Participant</td>
    <td align="center" width="100%">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center"> 
      <input type="checkbox" name="roles[]" value="2">
    </td>
    <td align="center"> 
      <input type="checkbox" name="roles[]" value="3">
    </td>
    <td align="center"> 
      <input type="checkbox" name="roles[]" value="4">
    </td>
    <td align="center"> 
      <input type="checkbox" name="roles[]" value="5">
    </td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td height="1"><img height="1" width="60" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="75" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="60" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="80" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
</table>
<br>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <? if (!count($conferences)==0)
     { ?>
    	<td class="textBold">Please select the conferences to invite to :</td>
  <? }
     else
	 { ?>
	 	<td class="textBold">There are no conferences you can choose.</td>
  <? } ?>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="30" class="textBold">Invite</td>
    <td width="100%" class="textBold">Conference</td>
  </tr>
  <? foreach($conferences as $conference)
 {  ?> 
  <tr> 
    <td width="30" class="text"><input type="checkbox" name="conferences[]" value="<? echo $conference['confID'] ?>"></td>
    <td width="100%" class="text"><? echo $conference['confName'] ?></td>
  </tr> 
<? } ?> 
  <tr> 
    <td width="80">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td width="80">&nbsp;</td>
    <td>
        <input type="submit" name="Submit" value="Invite">
    </td>
  </tr>
  <tr> 
    <td height="1" width="30"><img height="1" width="80" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>

</table>
</form>
<?
include("footer.tpl.php");
?>
