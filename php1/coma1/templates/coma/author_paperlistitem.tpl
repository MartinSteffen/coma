  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <a href="{basepath}author_editpaper.php?confid={confid}{&SID}">{title}</a>
    </td>
    <td class="listitem-{line_no}">&nbsp;</td>
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
      {if5<a href="{file_link}">}view paper{if5</a>}
    </td>
    <td class="listitem-{line_no}">&nbsp;</td>
    <td class="listitem-{line_no}">&nbsp;</td>
  </tr>
