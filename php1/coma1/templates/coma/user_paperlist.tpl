
<table class="list">
  <tr class="listheader">
    <th class="listheader">Title</th> 
    <th class="listheader">Author</th> 
    <th class="listheader">Status</th>    
    <th class="listheader">Rating</th>
    <th>&nbsp;</th>
  </tr>

  <tr class="listitem-1"> 
    <td class="listitem-1">
      <a href="{basepath}user_paperdetails.php?paperid={paperid}{&SID}">
      Neueste Rezepte</a>
    </td> 
    <td class="listitem-1"> 
      <a href="{basepath}user_userdetails.php?userid={authorid}{&SID}">
      Robby Rabbit</a>
    </td>
    <td class="listitem-1"> 
      <span class="status-accepted">accepted</span>
    </td>
    <td class="listitem-1">  3/5 </td> 
    <td class="listitem-1"> 
      <form action="" method="post" accept-charset="UTF-8">
        <input type="hidden" name="paperid" value="{paperid}" />
        <input type="submit" name="submit" value="view" class="button" />
      </form>
    </td>
  </tr>

  <tr class="listitem-2"> 
    <td class="listitem-2">
      <a href="{basepath}user_paperdetails.php?paperid={paperid}{&SID}">
      Hypnotics - Ways to escape student insomnia</a>
    </td> 
    <td class="listitem-2"> 
      <a href="{basepath}user_userdetails.php?userid={authorid}{&SID}">
      Robby Rabbit</a>
    </td>
    <td class="listitem-2"> 
      <span class="status-reviewed">reviewed</span>
    </td>
    <td class="listitem-2"> 4/5 </td> 
    <td class="listitem-1">
      <form action="" method="post" accept-charset="UTF-8">
        <input type="hidden" name="paperid" value="{paperid}" />
        <input type="submit" name="submit" value="view" class="button" />
      </form>
    </td>
  </tr>
</table>

<p class="message2">
  Click on any paper to view the details for the paper.<br>
  Click on any author to view the details for the user.
</p>