 <tr class="listitem-{line_no}"> 
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&SID}">{title}</a>
    </td> 
    <td class="listitem-{line_no}"> 
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}">{author_name}</a>
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="status-unreviewed">unreviewed</span>}
      {if1<span class="status-reviewed">reviewed</span>}
      {if2<span class="status-critical">conflicting</span>}
      {if3<span class="status-accepted">accepted</span>}
      {if4<span class="status-rejected">rejected</span>}      
    </td>
    <td class="listitem-{line_no}">{avg_rating}</td>
    <td class="listitem-{line_no}">{last_edited}</td>
    <td class="listitem-{line_no}">
      {if5<button name="viewpaper" type="button" class="smallbutton" value="view paper"
                  onClick="self.location.href='{file_link}'">view paper</button>}
    </td>
    <td class="listitem-{line_no}">
      <form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="submit" value="delete" class="smallbutton">
      </form>
    </td>
  </tr>