      <td>
        <form action="{basepath}{target_form}{?SID}" method="post" accept-charset="UTF-8">
          <input type="hidden" name="action" value="editrole" class="button">
          <input type="hidden" name="userid" value="{user_id}">
          <input type="hidden" name="roletype" value="{role_type}">
          <span{if1 class="role_active"}{if2 class="role_passive"}{if3 class="role_request"}>{role_name}</span>
          {if1<input type="submit" name="submit" value="remove" class="smallbutton">}
          {if2<input type="submit" name="submit" value="add" class="smallbutton">}
          {if3<input type="submit" name="submit" value="activate" class="smallbutton">}
        </form> 
      </td>