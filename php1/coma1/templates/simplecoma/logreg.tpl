<div align="center">
<p class="message"> {message} </p>
</div>


<div align="center">
<table>
<tr> <td> <b> Einloggen </b> </td> </tr>

<tr>
  <td> <form action="{basepath}index.php{SID}" method="post"> 
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
  </form>
  </td>
</tr>
</table>
</div>


<div align="center">
<table>
<tr>
   <td> <b> Registrieren: </b>  </td>
</tr>
<tr>
  <td> <form action="{basepath}index.php{SID}" method="post"> Email-Addresse: </td>
  <td> <input type="text" name="userMail" size="30" maxlength="127" /> </td>
</tr>
<tr>
  <td> Password:  </td>
  <td> <input type="password" name="userPassword" size="30" maxlength="127" /> </td>
</tr>
<tr>
  <td> Wiederholung: </td>
  <td> <input type="password" name="userPassword" size="30" maxlength="127" /> 
  <input type="hidden" name="action" value="register" />
  <input type="submit" name="submit" value="Register" />
  </form> </td>
</tr>