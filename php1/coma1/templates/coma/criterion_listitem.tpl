        <tr class="formlistitem">
          <td class="formlistitem">
            <input type="text" name="crit_name-{crit_no}" size="32" maxlength="127" value="{crit_name}">
          </td>
          <td class="formlistitem">
            <input type="text" name="crit_max-{crit_no}" size="8" maxlength="8" value="{crit_max}">
          </td>
          <td class="formlistitem">
            <input type="text" name="crit_weight-{crit_no}" size="8" maxlength="8" value="{crit_weight}">
          </td>          
          <td class="formlistitem">
            <input type="submit" name="del_crit-{crit_no}" value="Remove" class="smallbutton">
          </td>          
        </tr>
        <tr>
          <td colspan="4">
            Description: <textarea name="crit-descr-{crit_no}" rows="1" cols="48">{crit_descr}</textarea>
          </td>
        </tr>
