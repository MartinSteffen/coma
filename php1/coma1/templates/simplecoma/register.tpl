
<p class="message" align="center"> {message} </p>


<div align="center">
<table>
<tr> <td> <b> Registrieren: <br>  <br> </b> </td>   <td>&nbsp; </td></tr>

<tr>
  <td valign="top"> 
       <form action="{basepath}index_regi.php{SID}" method="post">  


       Titel: <br>
       <input type="text" name="title" size="30" maxlength="127" /> <br> <br>

       Vorname: <br>
       <input type="text" name="first_name" size="30" maxlength="127" /> <br> <br>

       Name: <br>
       <input type="text" name="last_name" size="30" maxlength="127" /> <br> <br>


       Email-Addresse: <br>
       <input type="text" name="email" size="30" maxlength="127" /> <br> <br>

       Telefon: <br>
       <input type="text" name="telefon" size="30" maxlength="127" /> <br> <br>

       Fax: <br>
       <input type="text" name="fax" size="30" maxlength="127" /> <br> <br>

       Staﬂe: <br>
       <input type="text" name="street" size="30" maxlength="127" /> <br> <br>

       PLZ: <br>
       <input type="text" name="zip" size="30" maxlength="80" /> <br> 
       Ort: <br>
       <input type="text" name="city" size="30" maxlength="47" /> <br> <br>

       Passwort: <br> 
       <input type="password" name="userPassword" size="30" maxlength="127" /> <br> <br>

       Wiederholung: <br> 
       <input type="password" name="userPassword2" size="30" maxlength="127" /> <br> <br>
       
       <input type="hidden" name="action" value="register" /> <br> <br> 
       <input type="submit" name="submit" value="Register" /> <br> <br>
       </form> 
  </td>
</tr>
</table>
</div>