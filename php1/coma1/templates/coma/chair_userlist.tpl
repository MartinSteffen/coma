<table class="list">
  <tr class="listheader">
    <th width="25%" class="listheader"> Name </th> 
    <th width="25%" class="listheader"> Roles </th> 
    <th width="25%" class="listheader"> Add Role  </th>
  </tr>

  <tr class="ft-tr"> 
    <td class="listitem-1"> <a href=""> Robby Rabbit</a> </td> 
    <td class="listitem-1"> 
       <form action="" method="post"> 
       <input type="hidden" name="confid" value="{confid}"> 
       <input type="submit" name="submit" value="delete" class="button">
       chair 
       </form>  
       <form action="" method="post">   
       <input type="hidden" name="confid" value="{confid}">
       <input type="submit" name="submit" value="delete" class="button">
       reviewer
       </form> 
       <form action="" method="post"> 
       <input type="hidden" name="confid" value="{confid}">
       <input type="submit" name="submit" value="delete" class="button">
       author
       </form>
   </td> 
  </tr>

  <tr class="listitem-2"> 
    <td class="listitem-2"> <a href=""> Grinse Katze</a> </td> 
    <td class="listitem-2">  &nbsp; </td> 
    <td class="listitem-2">  
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}">
       <input type="submit" name="submit" value="add Chair" class="button">
       </form>
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}">
       <input type="submit" name="submit" value="add Reviewer" class="button">
       </form>
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}">
       <input type="submit" name="submit" value="add Author" class="button">
       </form>
    </td> 
  </tr>
</table>
