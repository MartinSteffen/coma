<?
include("header.tpl.php");
$input = d("register");
?>
<link rel="stylesheet" href="style.css" type="text/css">

<table width="576" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="textBold">Register to Comma.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><form name="form1" method="post" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="textBold" width="29%"><span class="textRed">*</span>Email 
              (for login)</td>
            <td width="37%"> 
              <input type="text" name="email" size="30" maxlength="127" value="<? echo $input['email'] ?>">
            </td>
            <td width="34%" class="textRed"> 
              <? echo $input['email_error'] ?>
            </td>
          </tr>
          <tr> 
            <td class="textBold" width="29%"><span class="textRed">*</span>Password</td>
            <td width="37%"> 
              <input type="password" name="pass" size="30" maxlength="127">
            </td>
            <td width="34%" class="textRed"> 
              <? echo $input['pass_error'] ?>
            </td>
          </tr>
          <tr> 
            <td class="textBold" width="29%"><span class="textRed">*</span>Password 
              (retype) </td>
            <td width="37%">
              <input type="password" name="passRetype" size="30" maxlength="127">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr>
            <td class="textBold" width="29%">&nbsp;</td>
            <td width="37%">&nbsp;</td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">Title</td>
            <td width="37%"> 
              <input type="text" name="title" size="30" maxlength="127" value="<? echo $input['title'] ?>">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">First name</td>
            <td width="37%"> 
              <input type="text" name="first_name" size="30" maxlength="127" value="<? echo $input['first_name'] ?>">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%"><span class="textRed">*</span>Last 
              name</td>
            <td width="37%"> 
              <input type="text" name="last_name" size="30" maxlength="127" value="<? echo $input['last_name'] ?>">
            </td>
            <td width="34%" class="textRed"> 
              <? echo $input['last_name_error'] ?>
            </td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">Affiliation</td>
            <td width="37%"> 
              <input type="text" name="affiliation" size="30" maxlength="127" value="<? echo $input['affiliation'] ?>">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">Street</td>
            <td width="37%"> 
              <input type="text" name="street" size="30" maxlength="127" value="<? echo $input['street'] ?>">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">Postal code</td>
            <td width="37%"> 
              <input type="text" name="postal" size="30" maxlength="127" value="<? echo $input['postal'] ?>">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">City</td>
            <td width="37%"> 
              <input type="text" name="city" size="30" maxlength="127" value="<? echo $input['city'] ?>">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">State</td>
            <td width="37%"> 
              <input type="text" name="state" size="30" maxlength="127" value="<? echo $input['state'] ?>">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">Country</td>
            <td width="37%"> 
              <input type="text" name="country" size="30" maxlength="127" value="<? echo $input['country'] ?>">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">Phone number</td>
            <td width="37%"> 
              <input type="text" name="phone" size="30" maxlength="127" value="<? echo $input['phone'] ?>">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">Fax number</td>
            <td width="37%"> 
              <input type="text" name="fax" size="30" maxlength="127" value="<? echo $input['fax'] ?>">
            </td>
            <td width="34%" class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold" width="29%">&nbsp;</td>
            <td width="37%"> 
              <input type="submit" name="Submit" value="Register">
            </td>
            <td width="34%">&nbsp;</td>
          </tr>
        </table>
	  </form>
    </td>
  </tr>
</table>



<?
include("footer.tpl.php");
?>