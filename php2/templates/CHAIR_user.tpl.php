<? 
include("header.tpl.php");
$input = d('chair');
$person = $input['person'];
$roles = $input['roles'];
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
  <tr> 
    <td align="left" valign="middle" width="35"><img src="templates/images/arrow.gif" width="30" height="17"></td>
    <td align="left" valign="middle" width="100%"><a href="index.php?m=chair&a=papers&s=allPapersOfUser&userID=<?echo $person['personID'] ?>" class="menus">View 
      all papers of the user</a></td>
  </tr>
  <tr> 
    <td height="1"><img height="1" width="35" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="middle" class="textBold">Roles:</td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="textBold" width="248" height="30">Conference</td>
    <td class="textBold" width="86" height="30">Role</td>
    <td class="textBold" width="100" height="30">State</td>
    <td class="textBold" width="100%" height="30">&nbsp;</td>
  </tr>
  <?
$count = 0;
foreach ($roles as $selRole)
{
?>
  <form name="rolesForm<? echo $count ?>" method="post" action="index.php?m=chair&a=users&s=deleteRole">
    <input type="hidden" name="personID" value="<? echo $person['personID'] ?>">
	<input type="hidden" name="confID" value="<? echo $selRole['confID'] ?>">
	<input type="hidden" name="roleType" value="<? echo $selRole['roleType'] ?>">
    <tr valign="middle"> 
      <td class="text" width="248" height="30"> 
        <? echo $selRole['confName'] ?>
      </td>
      <td class="text" width="86" height="30"> 
        <? echo $selRole['roleTypeText'] ?>
      </td>
      <td class="<? echo $selRole['stateColor'] ?>" width="100" height="30"> 
        <? echo $selRole['stateText'] ?>
      </td>
   <? if ($selRole['roleType'] == 1)
      {  ?>
      <td class="textBold" width="100%" height="30">&nbsp;</td>
   <? }
      else
	  {  ?>
      <td class="text" width="100%" height="30"> 
        <input type="submit" name="Submit" value="Delete role" onclick="return confirm('Are you sure you want to delete this role?')">
      </td>	  
   <? }  ?>
    </tr>
  </form>
  <?
$count++;
}
?>
    <tr> 
      <td height="1"><img height="1" width="248" src="/templates/images/spacer.gif"></td>
      <td><img height="1" width="86" src="/templates/images/spacer.gif"></td>
      <td><img height="1" width="100" src="/templates/images/spacer.gif"></td>
      <td></td>
    </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="middle" width="35"><img src="templates/images/arrow.gif" width="30" height="17"></td>
    <td align="left" valign="middle" width="100%"><a href="index.php?m=chair&a=users&s=inviteUser&userID=<?echo $person['personID'] ?>" class="menus">Invite the user to a role</a></td>
  </tr> 
  <tr> 
    <td height="1"><img height="1" width="35" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>  
</table>
<br>
<?
include("footer.tpl.php");
?>
