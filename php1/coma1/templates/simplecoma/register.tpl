
<p class="message" align="center"> {message} </p>


<div align="center">
<table>
<tr> <td> <b> Registrieren: <br>  <br> </b> </td>   <td>&nbsp; </td></tr>

<tr>
  <td valign="top"> 
       <form action="{basepath}index.php{SID}" method="post">  

       Konferenzname: <br>
       <input type="text" name="konverenzname" size="30" maxlength="127" /> <br> <br>

       Email-Addresse: <br>
       <input type="text" name="userMail" size="30" maxlength="127" /> <br> <br>

       Passwort: <br> 
       <input type="password" name="userPassword" size="30" maxlength="127" /> <br> <br>

       Wiederholung: <br> 
       <input type="password" name="userPassword" size="30" maxlength="127" /> <br> <br>
       <input type="hidden" name="action" value="register" /> <br> <br> 
       <input type="submit" name="submit" value="Register" /> <br> <br>
       </form> 
  </td>
</tr>