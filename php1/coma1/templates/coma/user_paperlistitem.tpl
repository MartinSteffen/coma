 <tr class="listitem-{line-no}"> 
    <td class="listitem-{line-no}">
      <a href="{basepath}user_paperdetails.php?paperid={paperid}{&SID}">{title}</a>
    </td> 
    <td class="listitem-{line-no}"> 
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}">{author_name}</a>
    </td>
    <td class="listitem-{line-no}">
      {if0<span class="status-unreviewed">unreviewed</span>}
      {if1<span class="status-reviewed">reviewed</span>}
      {if2<span class="status-critical">conflicting</span>}
      {if3<span class="status-accepted">accepted</span>}
      {if4<span class="status-rejected">rejected</span>}      
    </td>
    <td class="listitem-{line-no}">{avg_rating}</td>
    <td class="listitem-{line-no}">{last_edited}</td>
    <td class="listitem-{line-no}">
      {if5<a href="{file_link}">}view paper{if5</a>}
    </td>
    <td class="listitem-{line-no}">&nbsp;</td>
    <td class="listitem-{line-no}">&nbsp;</td>
  </tr>