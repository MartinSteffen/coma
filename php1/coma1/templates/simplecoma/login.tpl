
<p class="message" align="center"> {message} </p>


<div align="center">
<table>
<tr> <td> <b> Einloggen </b> </td> </tr>
<form action="{basepath}index.php{SID}" method="post"> 
<tr>
  <td>
       Email-Addresse: 
  </td>
  <td> <input type="text" name="userMail" size="30" maxlength="127" /> &nbsp; &nbsp;
  </td>
</tr>
<tr>
  <td> Password: </td>
  <td> <input type="password" name="userPassword" size="30" maxlength="127" />
  <input type="hidden" name="action" value="login" />
  <input type="submit" name="submit" value="Login" />
  </td>
</tr>
 </form>
</table>
</div>
