<?
include("header.tpl.php");
$message = $TPL['login'];
?>

<table width="300" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="textRed"> 
      <? echo $message ?>
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td class="textBold">Login to Coma.</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td> 
      <form name="form1" method="post" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="textBold" width="27%">Email</td>
            <td width="73%"> 
              <input type="text" name="email" size="30" maxlength="127">
            </td>
          </tr>
          <tr> 
            <td class="textBold" width="27%">Password</td>
            <td width="73%"> 
              <input type="password" name="pass" size="30" maxlength="127">
            </td>
          </tr>
          <tr> 
            <td width="27%">&nbsp;</td>
            <td width="73%"> 
              <input type="submit" name="Submit" value="Login">
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"> 
      <p><span class="textBold">Not a member yet?</span> <a href="index.php?m=register" class="menus">Register 
        now</a>!</p>
      <p><span class="text">(It is strong </span><span class="textBold">recommended</span><span class="text"> 
        that you use <br>
        a browser with </span><span class="textBold">javascript</span> <span class="text">enabled!)</span></p>
    </td>
  </tr>
</table>
<?
include("footer.tpl.php");
?>
