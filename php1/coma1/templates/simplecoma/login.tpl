
<p class="message" align="center"> {message} </p>


<div align="center">
<table>
<tr> <td>&nbsp; </td><td> <b> Einloggen <br>  <br> </b> </td> </tr>
<tr> 
  <td valign="middle">
        Email-Addresse: <br> <br>
        Passwort: <br> <br> <br>
  </td>
  <td>
       <form action="{basepath}index.php{SID}" method="post"> 
       <input type="text" name="userMail" size="30" maxlength="127" />  <br> <br>
       <input type="password" name="userPassword" size="30" maxlength="127" /><br>
       <input type="hidden" name="action" value="login" />
       <input type="submit" name="submit" value="Login" />
       </form>
  </td>
</tr>
</table>
</div>
