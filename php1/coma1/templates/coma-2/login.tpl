<p class="center">
<form action="{basepath}index.php?{SID}" method="post">
<table class="formtable">
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
      <input type="submit" name="submit" value="Login" class="button" />
    </td>
  </tr>
</table>
</form>
</p>

<p>
Falls Sie noch keinen Account besitzen, k&ouml;nnen Sie sich hier als Benutzer
registrieren.
<form action="{basepath}index.php?{SID}" method="post">
  <input type="submit" name="submit" value="Registrieren" class="button" />
</form>
</p>
