  <tr class="listitem-1"> 
    <td class="listitem-{line_no}">
      <a href="{basepath}user_userdetails.php?userid={user_id}{&SID}">{name}</a>
    </td> 
    <td class="listitem-{line_no}">
      <a href="{email_link}" class="email">{email}</a>
    </td>    
    <td class="listitem-{line_no}">
      <table>
        <tr>
          {roles}
        </tr>
      </table>
    </td>
    <td class="listitem-{line_no}"> 
      <form action="{basepath}{target_form}{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="userid" value="{user_id}">
        <input type="submit" name="submit" value="delete" class="button">
      </form>
    </td>
  </tr>