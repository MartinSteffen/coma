
{if1<p class="message">{message}</p>}
{if2<p class="message-ok">{message}</p>}

<form action="{basepath}login.php{?SID}" method="post" accept-charset="UTF-8">

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


<p>&nbsp;</p>

<p class="message2">
  If you have registered for the site, please enter your username and password.
  You have to login in order to access information that is relevant to you.<br>
  If you have not registered yet, please register below:<br>
</p>

<form action="{basepath}register.php{?SID}" method="post" accept-charset="UTF-8">
  <input type="submit" name="submit" value="Register" class="button" />
</form>

<p>&nbsp;</p>