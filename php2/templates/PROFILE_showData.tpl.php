<?
include("header.tpl.php");
$input = d("profile");
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="textBold" width="100%">Change your data.</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td> 
      <form name="form1" method="post" action="index.php?m=profile&a=data&s=saveData">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="textBold" width="167"><span class="textRed">*</span>Email 
              (for login)</td>
            <td width="213"> 
              <input type="text" name="email" size="30" maxlength="127" value="<? echo $input['email'] ?>">
            </td>
            <td width="100%" class="textRed"> 
              <? echo $input['email_error'] ?>
            </td>
          </tr>
          <tr> 
            <td class="textBold">&nbsp;</td>
            <td>&nbsp;</td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold">Title</td>
            <td> 
              <input type="text" name="title" size="30" maxlength="32" value="<? echo $input['title'] ?>">
            </td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold">First name</td>
            <td> 
              <input type="text" name="first_name" size="30" maxlength="127" value="<? echo $input['first_name'] ?>">
            </td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold"><span class="textRed">*</span>Last name</td>
            <td> 
              <input type="text" name="last_name" size="30" maxlength="127" value="<? echo $input['last_name'] ?>">
            </td>
            <td class="textRed"> 
              <? echo $input['last_name_error'] ?>
            </td>
          </tr>
          <tr> 
            <td class="textBold">Affiliation</td>
            <td> 
              <input type="text" name="affiliation" size="30" maxlength="127" value="<? echo $input['affiliation'] ?>">
            </td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold">Street</td>
            <td> 
              <input type="text" name="street" size="30" maxlength="127" value="<? echo $input['street'] ?>">
            </td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold">Postal code</td>
            <td> 
              <input type="text" name="postal" size="20" maxlength="20" value="<? echo $input['postal'] ?>">
            </td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold">City</td>
            <td> 
              <input type="text" name="city" size="30" maxlength="127" value="<? echo $input['city'] ?>">
            </td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold">State</td>
            <td> 
              <input type="text" name="state" size="30" maxlength="127" value="<? echo $input['state'] ?>">
            </td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold">Country</td>
            <td> 
              <input type="text" name="country" size="30" maxlength="127" value="<? echo $input['country'] ?>">
            </td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold">Phone number</td>
            <td> 
              <input type="text" name="phone" size="20" maxlength="20" value="<? echo $input['phone'] ?>">
            </td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold">Fax number</td>
            <td> 
              <input type="text" name="fax" size="20" maxlength="20" value="<? echo $input['fax'] ?>">
            </td>
            <td class="textRed">&nbsp;</td>
          </tr>
          <tr> 
            <td class="textBold">&nbsp;</td>
            <td> 
              <input type="submit" name="Submit" value="Change">
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td height="1"><img height="1" width="167" src="/templates/images/spacer.gif"></td>
            <td><img height="1" width="213" src="/templates/images/spacer.gif"></td>
            <td></td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>



<?
include("footer.tpl.php");
?>