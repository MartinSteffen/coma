<? 
include("header.tpl.php");
$roles = d('profile');
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
 <? if (!count($roles)==0)
    { ?>
    <td align="left" valign="middle" class="textBold">View and manage your roles:</td>
 <? }
    else
	{ ?>
    <td align="left" valign="middle" class="textBold">You have no roles.</td>	
 <?  } ?>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="textBold" width="248" height="30">Conference</td>
    <td class="textBold" width="86" height="30">Role</td>
    <td class="textBold" width="100" height="30">State</td>
    <td class="textBold" width="120" height="30">&nbsp;</td>
    <td class="textBold" width="100%" height="30">&nbsp;</td>	
  </tr>
  <?
$count = 0;
foreach ($roles as $selRole)
{
?>
  <form name="rolesForm<? echo $count ?>" method="post" action="index.php?m=profile&a=roles&s=updateRole">
	<input type="hidden" name="confID" value="<? echo $selRole['confID'] ?>">
	<input type="hidden" name="roleType" value="<? echo $selRole['roleType'] ?>">
	<input type="hidden" name="state" value="<? echo $selRole['state'] ?>">	
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
   <? if ($selRole['state'] == 3)
      {  ?>
      <td class="text" width="120" height="30"> 
        <input type="submit" name="Accept" value="Accept role">
      </td>		  
   <? }
      else
	  {  ?>
      <td class="textBold" width="100" height="30">&nbsp;</td> 
   <? } 
      if ($selRole['state'] == 2)
      {  ?>   
      <td class="textBold" width="100" height="30">&nbsp;</td> 	  
   <? }
      else
	  {  ?>	  
      <td class="text" width="100%" height="30"> 
        <input type="submit" name="Deny" value="Deny role" onclick="return confirm('Are you sure you want to deny this role?')">
      </td>
   <? } ?>
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
      <td><img height="1" width="120" src="/templates/images/spacer.gif"></td>	  
      <td></td>
    </tr>
</table>
<br>
<?
include("footer.tpl.php");
?>
