
<h2>Neuen Benutzer registrieren:</h2>

{message}

<p class="center">
<form action="{basepath}register.php?{SID}" method="post">

<table class="formtable">
  <tr>
    <th colspan="2">Benutzerdaten:</th>
  </tr>
  <tr>
    <td> 
      Vorname:
    </td>
    <td>      
      <input type="text" name="first_name" size="32" maxlength="127" />
    </td>
  </tr>
  <tr>
    <td> 
      Nachname:
    </td>
    <td>      
      <input type="text" name="last_name" size="32" maxlength="127" /> *
    </td>
  </tr>
  <tr>
    <td> 
      Email-Adresse:
    </td>
    <td>      
      <input type="text" name="email" size="32" maxlength="127" /> *
    </td>
  </tr>
  <tr>
    <td> 
      Titel:
    </td>
    <td>      
      <input type="text" name="name_title" size="16" maxlength="32" />
    </td>
  </tr>
  <tr>
    <td> 
      Telefon:
    </td>
    <td>      
      <input type="text" name="phone" size="16" maxlength="20" />
    </td>
  </tr>
  <tr>
    <td> 
      Fax:
    </td>
    <td>      
      <input type="text" name="fax" size="16" maxlength="20" />
    </td>
  </tr>
  <tr>
    <td> 
      Stra&szlig;e:
    </td>
    <td>      
      <input type="text" name="street" size="32" maxlength="127" />
    </td>
  </tr>
  <tr>
    <td> 
      PLZ:
    </td>
    <td>      
      <input type="text" name="postalcode" size="16" maxlength="20" /> 
    </td>
  </tr>
  <tr>
    <td> 
      Ort:
    </td>
    <td>      
      <input type="text" name="city" size="32" maxlength="127" />
    </td>
  </tr>
  <tr>
    <td> 
      Passwort:
    </td>
    <td>      
      <input type="password" name="user_password" size="32" maxlength="127" /> *
    </td>
  </tr>
  <tr>
    <td>
      Passwort wiederholen:
    </td>
    <td>
      <input type="password" name="password_repeat" size="32" maxlength="127" /> *
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="submit" name="submit" value="Registrieren" class="button" />      
    </td>
  </tr>
</table>
</form>
</p>

<p align="right">
<form action="{basepath}login.php?{SID}" method="post">
  <input type="submit" name="cancel" value="Abbrechen" class="button" />
</form>
</p>

<p>&nbsp;</p>