{if1<p class="message-failed">{message}</p>}
{if2<p class="message-ok">{message}</p>}

<form action="{basepath}lostpw.php{?SID}" method="post" accept-charset="UTF-8">

<table class="formtable">
  <tr>
    <th colspan="2">Your Username (E-mail):</th>
  </tr>
  <tr>
    <td>Email Address:</td>
    <td>
      <input type="text" name="user_name" size="30" maxlength="127" value="{uname}">
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="submit" name="submit" value="get new Password" class="button">
    </td>
  </tr>
</table>
</form>

<p>&nbsp;</p>

<p class="message">
  Please enter your Email address and proceed according to the Email you get.<br>
  If you have not registered yet, <a href="{basepath}register.php{?SID}" class="link">please register!</a>
</p>
