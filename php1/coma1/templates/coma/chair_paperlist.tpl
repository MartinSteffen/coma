
<table class="list">
  <tr class="listheader">
    <th class="listheader">Title</th> 
    <th class="listheader">Author</th> 
    <th class="listheader">Status</th>    
    <th class="listheader">Rating</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">&nbsp;</th>
  </tr>

  <tr class="listitem-1"> 
    <td class="listitem-1">
      <a href="{basepath}chair_paperdetails.php?paperid={paperid}{&SID}">
      Neueste Rezepte</a>
    </td> 
    <td class="listitem-1"> 
      <a href="{basepath}chair_userdetails.php?userid={authorid}{&SID}">
      Robby Rabbit</a>
    </td>
    <td class="listitem-1"> 
      <span class="status-accepted">accepted</span>
    </td>
    <td> 3/5 </td> 
    <td class="listitem-1">
      <form action="" method="post">
        <input type="hidden" name="paperid" value="{paperid}" />
        <input type="submit" name="submit" value="reset status" class="button" />        
      </form>
    </td>
    <td>
      <form action="" method="post">
        <input type="hidden" name="paperid" value="{paperid}" />
        <input type="submit" name="submit" value="view" class="button" />
        <input type="submit" name="submit" value="delete" class="button" />
      </form>
    </td>
  </tr>

  <tr class="listitem-2"> 
    <td class="listitem-2">
      <a href="{basepath}chair_paperdetails.php?paperid={paperid}{&SID}">
      Hypnotics - Ways to escape student insomnia</a>
    </td> 
    <td class="listitem-2"> 
      <a href="{basepath}chair_userdetails.php?userid={authorid}{&SID}">
      Robby Rabbit</a>
    </td>
    <td class="listitem-2"> 
      <span class="status-reviewed">reviewed</span>
    </td>
    <td> 4/5 </td> 
    <td class="listitem-2">
      <form action="" method="post">
        <input type="hidden" name="paperid" value="{paperid}" />
        <input type="submit" name="submit" value="accept" class="button" />
        <input type="submit" name="submit" value="reject" class="button" />
      </form>
    </td>
    <td>
      <form action="" method="post">
        <input type="hidden" name="paperid" value="{paperid}" />
        <input type="submit" name="submit" value="view" class="button" />
        <input type="submit" name="submit" value="delete" class="button" />
      </form>
    </td>
  </tr>

</table>