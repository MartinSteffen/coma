      <td>
        <form action="{basepath}{target_form}{?SID}" method="post" accept-charset="UTF-8">
          <input type="hidden" name="action" value="editrole" class="button">
          <input type="hidden" name="userid" value="{user_id}">
          <input type="hidden" name="roletype" value="{role_type}">
          <span{if1 class="role_active"}{if2 class="role_passive"}>{role_name}</span>
          {if1<input type="submit" name="submit" value="delete" class="smallbutton">}
          {if2<input type="submit" name="submit" value="add" class="smallbutton">}          
        </form> 
      </td>