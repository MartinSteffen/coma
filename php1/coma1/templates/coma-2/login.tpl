<form action="{basepath}index.php?{SID}" method="post">
<table align="center">
  <tr>
    <th colspan="2">Login:</th>
  </tr>
  <tr>
    <td>Email-Addresse:</td>
    <td>
      <input type="text" name="userMail" size="30" maxlength="127" />
    </td>
  </tr>
  <tr>
    <td>Passwort:</td>
    <td>
       <input type="password" name="userPassword" size="30" maxlength="127" />
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="submit" name="submit" value="Login" />
    </td>
  </tr>
</table>
</form>