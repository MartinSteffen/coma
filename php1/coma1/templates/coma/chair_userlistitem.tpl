  <tr class="listitem-1"> 
    <td class="listitem-1">
      <a href="{basepath}user_userdetails.php?userid={userid}{&SID}">
      Robby Rabbit</a>
    </td> 
    <td class="listitem-1"> 
      <form action="" method="post" accept-charset="UTF-8">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        chair
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
      <form action="" method="post" accept-charset="UTF-8">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        reviewer
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
    </td>
    <td class="listitem-1"> 
      <form action="" method="post" accept-charset="UTF-8">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        author
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
      <form action="" method="post" accept-charset="UTF-8">
        <input type="hidden" name="userid" value="{userid}">
        <input type="hidden" name="roletype" value="{roletype}">
        participant
        <input type="submit" name="submit" value="deleterole" class="button">       
      </form> 
    </td>
    <td>
      <form action="" method="post" accept-charset="UTF-8">
        <input type="hidden" name="userid" value="{userid}" />
        <input type="submit" name="submit" value="delete" class="button" />
      </form>
    </td>
  </tr>