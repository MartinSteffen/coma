  <tr class="listitem-{line_no}"> 
    <td class="listitem-{line_no}">
      <a href="{basepath}reviewer_editreview.php?reviewid={review_id}{&SID}" class="link">{title}</a>
    </td> 
    <td class="listitem-{line_no}">
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}" class="link">{author_name}</a>
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="status-unreviewed">unreviewed</span>}
      {if1<span class="status-reviewed">reviewed</span>}
      {if2<span class="status-critical">conflicting</span>}
      {if3<span class="status-accepted">accepted</span>}
      {if4<span class="status-rejected">rejected</span>}      
    </td>
    <td class="listitem-{line_no}">{rating}</td>
    <td class="listitem-{line_no}">{avg_rating}</td>    
  </tr>
  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}" colspan="5">
      <form action="{basepath}reviewer_editreview.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="reviewid" value="{review_id}">
        <input type="submit" name="editreview" value="Edit review" class="button">
      </form>      
      <form action="{basepath}user_paperdetails.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="view" value="See paper details" class="button">
      </form>      
      <form action="{basepath}forum.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="forum" value="Enter discussion" class="button">
      </form>      
    </td>
  </tr>