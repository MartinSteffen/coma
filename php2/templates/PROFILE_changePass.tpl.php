<?
include("header.tpl.php");
$input = d("profile");
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="textBold" width="100%">Change your password.</td>
  </tr>
  <tr> 
    <td class="textRed"> 
      <? echo $input['pass_error'] ?>
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td> 
      <form name="form1" method="post" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="textBold" width="206"><span class="textRed">*</span>Old 
              password </td>
            <td width="100%"> 
              <input type="password" name="oldPass" size="30" maxlength="127">
            </td>
          </tr>
          <tr> 
            <td class="textBold" width="206"><span class="textRed">*</span>New Password</td>
            <td> 
              <input type="password" name="pass" size="30" maxlength="127">
            </td>
          </tr>
          <tr> 
            <td class="textBold" width="206"><span class="textRed">*</span>New 
              Password (retype) </td>
            <td> 
              <input type="password" name="passRetype" size="30" maxlength="127">
            </td>
          </tr>
          <tr> 
            <td class="textBold" width="206">&nbsp;</td>
            <td> 
              <input type="submit" name="Submit" value="Change">
            </td>
          </tr>
          <tr> 
            <td height="1" width="206"><img height="1" width="206" src="/templates/images/spacer.gif"></td>
            <td></td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>



<?
include("footer.tpl.php");
?>