{if1<p class="message-failed">{message}</p>}
{if2<p class="message-ok">{message}</p>}

<form action="{basepath}lostpw.php{?SID}" method="post" accept-charset="UTF-8">

<table class="formtable">
  <tr>
    <th colspan="2">Your Username (E-mail):</th>
  </tr>
  <tr>
    <td>E-mail Address:</td>
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
  Please enter your E-Mail Address and proceed according to the E-mail you get.
</p>
