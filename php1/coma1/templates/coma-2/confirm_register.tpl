
<h2>Neuen Benutzer registrieren:</h2>

<p class="center">
<form action="{basepath}confirm_register.php?{SID}" method="post">

<table class="formtable">
  <tr>
    <th colspan="2">Neuen Benutzer angelegt:</th>
  </tr>
  <tr>
    <td> 
      Name:
    </td>
    <td>      
      {name_title} {first_name} {last_name}
    </td>
  </tr>
  <tr>
    <td> 
      Email-Adresse:
    </td>
    <td>      
      {email} <div class="emph">(dient als Benutzername)</div>
    </td>
  </tr>
  <tr>
    <td> 
      Telefon:
    </td>
    <td>      
      {phone}
    </td>
  </tr>
  <tr>
    <td> 
      Fax:
    </td>
    <td>      
      {fax}
    </td>
  </tr>
  <tr>
    <td> 
      Adresse: 
    </td>
    <td>      
      {street}, {postalcode} {city}
    </td>
  </tr>
</table>
</p>

{message}

<p>&nbsp;</p>