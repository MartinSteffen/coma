  <tr class="listitem-{line_no}"> 
    <td class="listitem-{line_no}">
      <a href="{basepath}reviewer_editreview.php?reviewid={review_id}{&SID}">{title}</a>
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
    <td class="listitem-{line_no}">{rating}/{max_rating}</td>
    <td class="listitem-{line_no}">{avg_rating}/{max_rating}</td>    
  </tr>
  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}" colspan="5">
      <form action="" method="post" accept-charset="UTF-8">
        <input type="hidden" name="reviewid" value="{reviewid}" />
        <input type="submit" name="submit" value="Edit review" class="button" />
        <input type="submit" name="submit" value="See paper details" class="button" />
        <input type="submit" name="submit" value="Enter discussion" class="button" />
      </form> 
    </td>
  </tr>