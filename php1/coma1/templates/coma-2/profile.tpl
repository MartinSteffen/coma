
{message}

<p class="center">
<form action="{basepath}profile.php?{SID}" method="post">

<table class="formtable">
  <tr>
    <th colspan="2">Benutzerdaten:</th>
  </tr>
  <tr>
    <td> 
      Vorname:
    </td>
    <td>      
      <input type="text" name="first_name" size="32" maxlength="127" value="{first_name}" />
    </td>
  </tr>
  <tr>
    <td> 
      Nachname:
    </td>
    <td>      
      <input type="text" name="last_name" size="32" maxlength="127" value="{last_name}"/ > *
    </td>
  </tr>
  <tr>
    <td> 
      Email-Adresse:
    </td>
    <td>      
      <input type="text" name="email" size="32" maxlength="127" value="{email}"/ > *
    </td>
  </tr>
  <tr>
    <td> 
      Titel:
    </td>
    <td>      
      <input type="text" name="name_title" size="16" maxlength="32" value="{name_title}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Telefon:
    </td>
    <td>      
      <input type="text" name="phone" size="16" maxlength="20" value="{phone}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Fax:
    </td>
    <td>      
      <input type="text" name="fax" size="16" maxlength="20" value="{fax}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Stra&szlig;e:
    </td>
    <td>      
      <input type="text" name="street" size="32" maxlength="127" value="{street}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      PLZ:
    </td>
    <td>      
      <input type="text" name="postalcode" size="16" maxlength="20" value="{postalcode}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Ort:
    </td>
    <td>      
      <input type="text" name="city" size="32" maxlength="127" value="{city}"/ >
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="hidden" name="action" value="update" />
      <input type="submit" name="submit" value="&Auml;nderungen &uuml;bernehmen" class="button" />
      <input type="reset"  name="reset" value="Einstellungen zur&uuml;setzen" class="button" />
    </td>
  </tr>
</table>
</form>
</p>
