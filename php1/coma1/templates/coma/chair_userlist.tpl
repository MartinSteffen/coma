<table class="list">
  <tr class="listheader">
    <th class="listheader">Name</th> 
    <th class="listheader">Roles</th> 
    <th class="listheader">Add Role</th>
    <th class="listheader">&nbsp;</th>
  </tr>

  <tr class="listitem-1"> 
    <td class="listitem-1">
      <a href="{basepath}chair_userdetails.php?userid={userid}{&SID}">
      Robby Rabbit</a>
    </td> 
    <td class="listitem-1"> 
      <form action="" method="post">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        chair
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
      <form action="" method="post">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        reviewer
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
    </td>
    <td class="listitem-1"> 
      <form action="" method="post">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        author
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
      <form action="" method="post">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        participant
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
    </td>
    <td>
      <form action="" method="post">
        <input type="hidden" name="userid" value="{userid}" />
        <input type="submit" name="submit" value="delete" class="button" />
      </form>
    </td>
  </tr>

  <tr class="listitem-2">
    <td class="listitem-2">
      <a href="{basepath}chair_userdetails.php?userid={userid}{&SID}">
      Grinse Katz</a>
    </td>
    <td class="listitem-1"> 
      <form action="" method="post">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        reviewer
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
    </td>
    <td class="listitem-1"> 
      <form action="" method="post">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        chair
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
      <form action="" method="post">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        author
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
      <form action="" method="post">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        participant
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
    </td>
    <td>
      <form action="" method="post">
        <input type="hidden" name="userid" value="{userid}" />
        <input type="submit" name="submit" value="delete" class="button" />
      </form>
    </td>
  </tr>
</table>
