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
    <td class="listitem-{line_no}">{rating}</td>
    <td class="listitem-{line_no}">{avg_rating}</td>    
  </tr>
  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}" colspan="5">
      <button name="edit" type="button" class="button" value="Edit review"
              onClick="self.location.href='{basepath}reviewer_editreview.php?reviewid={review_id}{&SID}'">
       Edit review</button>
      <button name="paperdetails" type="button" class="button" value="See paper details"
              onClick="self.location.href='{basepath}user_paperdetails.php?paperid={paper_id}{&SID}'">
       See paper details</button>
      <button name="discuss" type="button" class="button" value="Enter discussion" onClick="">
       Enter discussion</button>
    </td>
  </tr>