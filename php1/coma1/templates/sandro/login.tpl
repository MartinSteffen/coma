<b>{message}</b><br /><br />
<form action="{basepath}login.php" method="post">
  Email-Addresse: <input type="text" name="userMail" size="30" maxlength="127" /><br />
  Password: <input type="password" name="userPassword" size="30" maxlength="127" /><br />
  <input type="hidden" name="action" value="login" /><br />
  <input type="submit" name="submit" value="Login" /><br />
</form>
