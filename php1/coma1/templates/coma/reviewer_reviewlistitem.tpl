  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&popup}{&SID}" class="link" target="_blank">{title}</a>
    </td>
    <td class="listitem-{line_no}">
      <a href="{basepath}user_userdetails.php?userid={author_id}{&popup}{&SID}" class="link" target="_blank">{author_name}</a>
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="status-unreviewed">unreviewed</span>}
      {if1<span class="status-reviewed">reviewed</span>}
      {if2<span class="status-critical">critical</span>}
      {if3<span class="status-accepted">accepted</span>}
      {if4<span class="status-rejected">rejected</span>}
    </td>
    <td class="listitem-{line_no}">{rating}</td>
    <td class="listitem-{line_no}">{avg_rating}</td>
  </tr>
  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}" colspan="5">
      <table width="100%">
        <tr>
          <td>
        {if6<a href="{basepath}reviewer_editreview.php?reviewid={review_id}{&SID}"
               class="buttonlink">Edit review</a>}&nbsp;
          </td>
          <td>
            <a href="{basepath}user_paperdetails.php?paperid={paper_id}&popup{&SID}"
               class="buttonlink">See paper details</a>
          </td>
          <td>
        {if7<a href="{basepath}reviewer_reviews.php?createforum&paperid={paper_id}{&SID}"
               class="buttonlink">Start discussion</a>}&nbsp;
        {if8<a href="{basepath}forum.php?forumsel={forum_id}{&SID}"
               class="buttonlink">Enter discussion</a>}&nbsp;
          </td>
          <td class="listitem-{line_no}">
            <a href="{basepath}reviewer_paperreviews.php?paperid={paper_id}{&SID}" class="buttonlink">View review report</a> 
          </td>
        </tr>
      </table>
    </td>
  </tr>