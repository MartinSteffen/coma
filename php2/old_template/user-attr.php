<form name="userForm" method="post" action="">
  <table width="100%" border="0">
    <tr>
      <td align="center" valign="top"> 
        <table width="570" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="342" align="center" valign="middle"><b>You can change your 
              properties.</b></td>
            <td width="218">&nbsp;</td>
          </tr>
        </table>
        <br>
        <table width="570" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="18%">Title</td>
            <td width="43%"> 
              <input type="text" name="title" maxlength="127" size="30" value="<? echo $title ?>">
            </td>
            <td width="39%"><font color="#990000"> </font></td>
          </tr>
          <tr> 
            <td width="18%">First name</td>
            <td width="43%">
              <input type="text" name="first_name" size="30" maxlength="127" value="<? echo $first_name ?>">
            </td>
            <td width="39%"> <font color="#990000">
              <? echo $error['first_name'] ?>
              </font> </td>
          </tr>
          <tr> 
            <td width="18%">Last name</td>
            <td width="43%">
              <input type="text" name="last_name" size="30" maxlength="127" value="<? echo $last_name ?>">
            </td>
            <td width="39%"> <font color="#990000">
              <? echo $error['last_name'] ?>
              </font> </td>
          </tr>
          <tr> 
            <td width="18%">Afiliation</td>
            <td width="43%"> 
              <input type="text" name="afiliation" size="30" maxlength="127" value="<? echo $afiliation ?>">
            </td>
            <td width="39%">&nbsp; </td>
          </tr>
          <tr> 
            <td width="18%">Street</td>
            <td width="43%"> 
              <input type="text" name="street" size="30" maxlength="127" value="<? echo $street ?>">
            </td>
            <td width="39%">&nbsp; </td>
          </tr>
          <tr> 
            <td width="18%">Postal Code</td>
            <td width="43%"> 
              <input type="text" name="postal_code" size="30" maxlength="127" value="<? echo $postal_code ?>">
            </td>
            <td width="39%">&nbsp; </td>
          </tr>
          <tr> 
            <td width="18%">City</td>
            <td width="43%"> 
              <input type="text" name="city" size="30" maxlength="127" value="<? echo $city ?>">
            </td>
            <td width="39%">&nbsp; </td>
          </tr>
          <tr> 
            <td width="18%">State</td>
            <td width="43%"> 
              <input type="text" name="state" size="30" maxlength="127" value="<? echo $state ?>">
            </td>
            <td width="39%">&nbsp; </td>
          </tr>
          <tr> 
            <td width="18%">Country</td>
            <td width="43%"> 
              <input type="text" name="country" size="30" maxlength="127" value="<? echo $country ?>">
            </td>
            <td width="39%">&nbsp; </td>
          </tr>
          <tr> 
            <td width="18%">Phone</td>
            <td width="43%"> 
              <input type="text" name="phone" size="30" maxlength="127" value="<? echo $phone ?>">
            </td>
            <td width="39%">&nbsp; </td>
          </tr>
          <tr> 
            <td width="18%">Fax</td>
            <td width="43%"> 
              <input type="text" name="fax" size="30" maxlength="127" value="<? echo $fax ?>">
            </td>
            <td width="39%">&nbsp; </td>
          </tr>
          <tr> 
            <td width="18%">Email</td>
            <td width="43%"> 
              <input type="text" name="email" size="30" maxlength="127" value="<? echo $email ?>">
            </td>
            <td width="39%"> <font color="#990000"> 
              <? echo $error['email'] ?>
              </font> </td>
          </tr>
        </table>
        <table width="570" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="339" align="center" valign="middle"><font color="<? echo $colorHint ?>"><b> 
              <? echo $hint ?>
              </b></font> </td>
            <td width="221">&nbsp;</td>
          </tr>
        </table>
        <br>
        
        <table width="570" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" valign="middle" width="345"> 
              <input type="submit" name="Submit" value="Save"><br><br>
              <a href="index.php">Return to main page</a></td>
            <td align="center" valign="middle" width="225">&nbsp; </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>

