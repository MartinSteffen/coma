      <form action="{basepath}{target_form}{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="editrole" class="button">
        <input type="hidden" name="userid" value="{user_id}">
        <input type="hidden" name="roletype" value="{role_type}">
        <span{if1 class="role_active"}{if2 class="role_passive"}{role_name}
        {if1<input type="submit" name="submit" value="Add" class="button">}
        {if2<input type="submit" name="submit" value="Delete" class="button">}
      </form> 