
{if1<p class="message">{message}</p>}

<p class="center">
<form action="{basepath}login.php?{SID}" method="post">

<table class="formtable">
  <tr>
    <th colspan="2">Login:</th>
  </tr>
  <tr>
    <td>Email-Adresse:</td>
    <td>
      <input type="text" name="user_name" size="30" maxlength="127" />
    </td>
  </tr>
  <tr>
    <td>Passwort:</td>
    <td>
       <input type="password" name="user_password" size="30" maxlength="127" />
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="hidden" name="action" value="login" />
      <input type="submit" name="submit" value="Login" class="button" />
    </td>
  </tr>
</table>
</form>
</p>

<p>&nbsp;</p>

<p>
<form action="{basepath}register.php?{SID}" method="post">
  Falls Sie noch keinen Account besitzen, k&ouml;nnen Sie sich hier als Benutzer
  registrieren:<br>
  <input type="submit" name="submit" value="Registrieren" class="button" />
</form>
</p>

<p>&nbsp;</p>