<h2>Login:</h2>
<form action="{basepath}index.php?{SID}" method="post">
<table align="center">
  <tr>
    <td>Email-Addresse:</td>
    <td>Passwort:</td>
    <td>
      <input type="text" name="userMail" size="30" maxlength="127" />
    </td>
    <td>
       <input type="password" name="userPassword" size="30" maxlength="127" />
    </td>
  </td>
</tr>
</table>
  <input type="hidden" name="action" value="login" />
  <input type="submit" name="submit" value="Login" />
</form>