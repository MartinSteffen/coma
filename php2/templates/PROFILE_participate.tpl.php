<? 
include("header.tpl.php");
$conferences = d('profile');
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
<? if (count($conferences)==0)  
   { ?>
    <td class="textBold">There are no conferences to participate.</td>
<? } 
   else
   {  ?>
   <td class="textBold">Choose the conferences you would like to participate:</td>
<? }  ?>          
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="60" class="textBold" align="left" valign="top">Choose</td>
    <td width="100%" class="textBold" align="left" valign="top">Conference name</td>
  </tr>
  <tr> 
    <td width="60" class="textBold" align="left" valign="top">&nbsp;</td>
    <td width="100%" class="textBold" align="left" valign="top">&nbsp;</td>
  </tr>  
  <form name="form1" method="post" action="index.php?m=profile&a=participate">
<? foreach ($conferences as $conference)
   {  ?>  
  <tr> 
    <td width="60" class="text" align="left" valign="top">
        <input type="checkbox" name="conferences[]" value="<? echo $conference['confID'] ?>" <? if ($conference['check']==true) echo "checked" ?>>
      </td>
    <td width="100%" class="text" align="left" valign="top"><? echo $conference['confName'] ?></td>	  
  </tr> 
<?  }  ?> 
  <tr> 
    <td width="60" class="textBold" align="left" valign="top">&nbsp;</td>
    <td width="100%" class="textBold" align="left" valign="top">
        <input type="submit" name="Submit" value="Update">
      </td>
  </tr> 
  </form>               
  <tr> 
    <td height="1" width="117"><img height="1" width="60" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
</table>
<?
include("footer.tpl.php");
?>
