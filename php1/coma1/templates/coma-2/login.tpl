
{if1<p class="message">{message}</p>}

<p class="center">
<form action="{basepath}login.php?{SID}" method="post">

<table class="formtable">
  <tr>
    <th colspan="2">Login:</th>
  </tr>
  <tr>
    <td>E-mail Address:</td>
    <td>
      <input type="text" name="user_name" size="30" maxlength="127">
    </td>
  </tr>
  <tr>
    <td>Password:</td>
    <td>
       <input type="password" name="user_password" size="30" maxlength="127">
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="hidden" name="action" value="login">
      <input type="submit" name="submit" value="Login" class="button">
    </td>
  </tr>
</table>
</form>
</p>

<p>&nbsp;</p>

<p class="message2">
  If you have registered for the site, please enter your username and password.
  This will mean that you can get access to information relevant to you.
  If you have not registered, please register below:<br>
<form action="{basepath}register.php?{SID}" method="post">
  <input type="submit" name="submit" value="Register" class="button">
</form>
</p>

<p>&nbsp;</p>