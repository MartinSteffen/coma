        <tr class="formlistitem">          
          <td class="formlistitem">{crit_name}:</td>
          <td class="formlistitem">
            <input type="hidden" name="crit_id-{crit_no}" value="{crit_id}">
            <input type="text" size="2" name="rating-{crit_no}" value="{rating}"
            {if1 class="invalid"}>/{crit_max}
          </td>
          <td class="formlistitem">
            <textarea name="comment-{crit_no}" rows="1" cols="48">{comment}</textarea>
          </td>          
        </tr>
