 <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&popup}{&SID}" class="link">{title}</a>
    </td>
    <td class="listitem-{line_no}">
      {if6<a href="{basepath}user_userdetails.php?userid={author_id}{&popup}{&SID}" class="link">{author_name}</a>}
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="status-unreviewed">unreviewed</span>}
      {if1<span class="status-reviewed">reviewed</span>}
      {if2<span class="status-critical">critical</span>}
      {if3<span class="status-accepted">accepted</span>}
      {if4<span class="status-rejected">rejected</span>}
    </td>
    <td class="listitem-{line_no}">{avg_rating}</td>
    <td class="listitem-{line_no}" nowrap>{last_edited}</td>
    <td class="listitem-{line_no}">
      {if5<a href="{basepath}get_paper.php?paperid={paper_id}{&popup}{&SID}" class="buttonlink">view&nbsp;paper</a>}
    </td>
    <td class="listitem-{line_no}">&nbsp;</td>
    <td class="listitem-{line_no}">&nbsp;</td>
  </tr>