  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <a href="{basepath}author_editpaper.php?paperid={paper_id}{&SID}" class="link">{title}</a>
    </td>
    <td class="listitem-{line_no}">&nbsp;</td>
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
      {if5<a href="{basepath}get_paper.php?paperid={paper_id}{&SID}" class="buttonlink">view&nbsp;paper</a>}
    </td>
    <td class="listitem-{line_no}">
      <a href="{basepath}author_editpaper.php?paperid={paper_id}{&SID}" class="buttonlink">edit</a>
    </td>
    <td class="listitem-{line_no}">
  {if8<form action="{basepath}author_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="checkbox" name="confirm_delete" value="1">
        <input type="submit" name="submit" value="delete" class="smallbutton">
      </form>}&nbsp;
    </td>
  </tr>
