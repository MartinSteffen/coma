
<p class="message" align="center"> {message} </p>


<div align="center">
<table>
<tr> <td> <b> Einloggen: <br>  <br> </b> </td>   <td>&nbsp; </td></tr>
<tr>
  <td valign="top">
        Email-Addresse: <br> <br>
        Passwort: <br> <br>

  </td>
  <td valign="top">
       <form action="{basepath}index.php{SID}" method="post">
       <input type="text" name="userMail" size="30" maxlength="127" />  <br> <br>
       <input type="password" name="userPassword" size="30" maxlength="127" /><br> <br>
       <input type="hidden" name="action" value="login" />
       <input type="submit" name="submit" value="Login" />
       </form>
  </td>
</tr>
</table>
</div>
