<p class="message" align="center"> {message} </p>


<div align="center">

<form action="{basepath}index_regi.php{SID}" method="post">  
<table>
<tr>
  <td> <b>Registrieren:</b> <br> <br> </td>
  <td>&nbsp; </td>
</tr>
<tr>
  <td valign="top"> 
    Titel: <br>
    <input type="text" name="title" size="30" maxlength="127" /> <br> <br>
  </td>
  <td valign="top"> 
    Vorname: <br>
    <input type="text" name="first_name" size="30" maxlength="127" /> <br> <br>
  </td>
  <td valign="top"> 
    Name: <br>
    <input type="text" name="last_name" size="30" maxlength="127" /> <br> <br>
  </td>
</tr>
<tr>
  <td valign="top"> 
    Email-Addresse: <br>
    <input type="text" name="email" size="30" maxlength="127" /> <br> <br>
  </td>
</tr>
<tr>
  <td valign="top"> 
    Telefon: <br>
    <input type="text" name="telefon" size="30" maxlength="127" /> <br> <br>
  </td>
  <td valign="top"> 
    Fax: <br>
    <input type="text" name="fax" size="30" maxlength="127" /> <br> <br>
  </td>
</tr>
<tr>
  <td valign="top"> 
    Stra&szlig;e: <br>
    <input type="text" name="street" size="30" maxlength="127" /> <br> <br>
  </td>
</tr>
<tr>
  <td valign="top"> 
    PLZ: <br>
    <input type="text" name="zip" size="30" maxlength="80" /> <br> 
  </td>
  <td valign="top"> 
    Ort: <br>
    <input type="text" name="city" size="30" maxlength="47" /> <br> <br>
  </td>
</tr>
<tr>
  <td valign="top"> 
    Passwort: <br> 
    <input type="password" name="userPassword" size="30" maxlength="127" /> <br> <br>
  </td>
</tr>
<tr>
  <td valign="top"> 
    Wiederholung: <br> 
    <input type="password" name="userPassword2" size="30" maxlength="127" /> <br> <br>
  </td>
</tr>
<tr>
  <td valign="top">  
    <input type="hidden" name="action" value="register" /> <br> <br> 
    <input type="submit" name="submit" value="Register" /> <br> <br>
  </td>
</tr>
</table>
</form>
</div>