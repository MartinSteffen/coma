<? 
include("header.tpl.php");
$input = d('chair');
$confID = $input['confID'];
$confName = $input['confName']; 
$persons = $input['persons'];
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <? if (!count($persons)==0)
  	{ ?>
    <td align="left" valign="top"><span class="textBold">List of all users in conference :</span> <span class="text"> 
      <? echo $confName ?>
      </span></td>
  <? }
     else
	 { ?>
    <td align="left" valign="top"><span class="textBold">There are no users in this conference.</span>
      </td> 
  <? } ?>
  </tr>	  
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <? foreach($persons as $person) 
  { ?>
  <tr align="left" valign="top"> 
    <td width="125" class="textBold">User name</td>
    <td width="100%" class="text"><a href="index.php?m=chair&a=users&s=user&personID=<? echo $person['personID'] ?>" class="normal"><? echo $person['personName']; ?></a></td>
  </tr>
  <tr align="left" valign="top"> 
    <td width="125" class="textBold">Email</td>
    <td width="100%" class="text"><? echo $person['email']; ?></td>
  </tr>  
  <tr align="left" valign="top"> 
    <td width="125" class="textBold">Roles</td>
    <td width="100%" class="text"><? echo $person['roles']; ?></td>
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
