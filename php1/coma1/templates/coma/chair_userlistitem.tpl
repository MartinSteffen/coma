  <tr class="listitem-1">
    <td class="listitem-{line_no}">
      {ifALERT <img src="{path}images/alert.gif" width="15" height="15" border="0" alt="alert">}&nbsp;
    </td>
    <td class="listitem-{line_no}">
      <a href="{basepath}user_userdetails.php?userid={user_id}{&SID}" class="link">{name}</a>
    </td>
    <td class="listitem-{line_no}">
      <a href="{email_link}" class="email">{email}</a>
    </td>
    {roles}
    <td class="listitem-{line_no}">
      {if1<a href="{basepath}chair_prefers.php?userid={user_id}{&SID}" class="buttonlink">view&nbsp;preferences</a>}
    <!--
      <form action="{basepath}{targetform}{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="userid" value="{user_id}">
        <input type="submit" name="submit" value="delete" class="smallbutton">
      </form>
    -->
    </td>
  </tr>