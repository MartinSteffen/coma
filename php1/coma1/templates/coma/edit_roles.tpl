      <td class="listitem-{line_no}">
        <form action="{basepath}{targetform}{?SID}" method="post" accept-charset="UTF-8">
          <input type="hidden" name="action" value="editrole" class="button">
          <input type="hidden" name="userid" value="{user_id}">
          <input type="hidden" name="roletype" value="{role_type}">
          <span{if1 class="role_active"}{if2 class="role_passive"}{if3 class="role_request"}
               {if4 class="role_active"}>{role_name}</span>
          {if1<input type="submit" name="submit" value="remove" class="smallbutton">}
          {if2<input type="submit" name="submit" value="add" class="smallbutton">}
          {if3<br><input type="submit" name="submit" value="accept" class="smallbutton">
              <input type="submit" name="submit" value="reject" class="smallbutton">}
        </form>
      </td>