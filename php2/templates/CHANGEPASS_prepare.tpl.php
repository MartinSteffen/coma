<? 
include("header.tpl.php");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    	<td class="textBold">Forgot your password? No problem. You will get a new password from us.</td>
  </tr>
  <tr>
    	<td class="text">The new password will be randomly generated.</td>
  </tr>  
  <tr>
    	
    <td class="textBold">&nbsp;</td>
  </tr>  
  <tr>
    	<td class="textBold">Please enter your email.</td>
  </tr>      
</table>
<br>
<form name="form1" method="post" action="index.php?m=changePass&a=changePass&s=prepare">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="88" class="textBold">Email</td>
      <td width="327"> 
        <input type="text" name="email" maxlength="127" size="50">
      </td>
      <td width="100%" class="textRed"> 
        <? echo d(changePass)?>
      </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> 
        <input type="submit" name="Submit" value="Send">
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="1"><img height="1" width="88" src="/templates/images/spacer.gif"></td>
      <td><img height="1" width="327" src="/templates/images/spacer.gif"></td>
      <td></td>
    </tr>
  </table>
</form>
<?
include("footer.tpl.php");
?>