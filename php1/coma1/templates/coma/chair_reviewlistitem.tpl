  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&SID}" class="link">{title}</a>
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
    <td class="listitem-{line_no}">{num_reviews}</td>
    <td class="listitem-{line_no}">{avg_rating}</td>
    <td class="listitem-{line_no}">
      {if6<span class="status-critical">{crit_value}</span>}
      {if7{crit_value}}
    </td>
    <td class="listitem-{line_no}">
      {if5<a href="{basepath}get_paper.php?paperid={paper_id}{&SID}" class="link">view paper</a>}
    </td>
    <td class="listitem-{line_no}">                  
      {reviewers}
    </td>
    <td class="listitem-{line_no}">
      <!-- <form action="{basepath}chair_reviews.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="submit" value="add" class="smallbutton">
      </form> -->
    </td>
  </tr>