
{if9<p class="message-failed">{message}</p>}

<form action="{basepath}{targetpage}{?SID}" method="post" accept-charset="UTF-8">

<table class="formtable">
  <tr>
    <th colspan="2">User data:</th>
  </tr>
  <tr>
    <td>
      Old Password:
    </td>
    <td>
      <input type="password" name="password" size="48" maxlength="127" value="">
    </td>
  </tr>
  <tr>
    <td>
      Password:
    </td>
    <td>
      <input type="password" name="password1" size="48" maxlength="127" value="">
    </td>
  </tr>
  <tr>
    <td>
      Repeat Password:
    </td>
    <td>
      <input type="password" name="password2" size="48" maxlength="127" value="">
    </td>
  </tr>
  <tr>
    <td>
      First name:
    </td>
    <td>
      <input type="text" name="first_name" size="48" maxlength="127" value="{first_name}">
    </td>
  </tr>
  <tr>
    <td>
      <span class="alert">*</span>Last name:
    </td>
    <td>
      <input type="text" name="last_name" size="48" maxlength="127" value="{last_name}">
    </td>
  </tr>
  <tr>
    <td>
      <span class="alert">*</span>Email address:
    </td>
    <td>
      <input type="text" name="email" size="48" maxlength="127" value="{email}">
    </td>
  </tr>
  <tr>
    <td>
      Title:
    </td>
    <td>
      <input type="text" name="name_title" size="32" maxlength="32" value="{name_title}">
    </td>
  </tr>
  <tr>
    <td>
      Affiliation:
    </td>
    <td>
      <input type="text" name="affiliation" size="48" maxlength="127" value="{affiliation}">
    </td>
  </tr>
  <tr>
    <td>
      Phone number:
    </td>
    <td>
      <input type="text" name="phone" size="32" maxlength="32" value="{phone}">
    </td>
  </tr>
  <tr>
    <td>
      Fax number:
    </td>
    <td>
      <input type="text" name="fax" size="32" maxlength="32" value="{fax}">
    </td>
  </tr>
  <tr>
    <td>
      Street:
    </td>
    <td>
      <input type="text" name="street" size="48" maxlength="127" value="{street}">
    </td>
  </tr>
  <tr>
    <td>
      Postal code:
    </td>
    <td>
      <input type="text" name="postalcode" size="20" maxlength="20" value="{postalcode}">
    </td>
  </tr>
  <tr>
    <td>
      City:
    </td>
    <td>
      <input type="text" name="city" size="48" maxlength="127" value="{city}">
    </td>
  </tr>
  <tr>
    <td>
      State:
    </td>
    <td>
      <input type="text" name="state" size="48" maxlength="127" value="{state}">
    </td>
  </tr>
  <tr>
    <td>
      Country:
    </td>
    <td>
      <input type="text" name="country" size="48" maxlength="127" value="{country}">
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="hidden" name="action" value="update">
      <input type="submit" name="submit" value="Accept changes" class="button">
      <input type="reset"  name="reset" value="Reset settings" class="button">
    </td>
  </tr>
</table>

<table class="formtable">
  <tr>
    <td>Deactivate this account?
      <input type="checkbox" name="confirm_delete" value="1">
    </td>
    <td>
      <input type="submit" name="delete" value="Deactivate" class="button">
    </td>
  </tr>
</table>
</form>

<p>&nbsp;</p>

<p class="message">
  If you want to change your Email or your password, please enter your old password, too.<br>
</p>
