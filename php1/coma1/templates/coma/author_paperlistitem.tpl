  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <a href="{basepath}author_editpaper.php?paperid={paper_id}{&SID}">{title}</a>
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
      {if5<button name="viewpaper" type="button" class="smallbutton" value="view paper"
                  onClick="self.location.href='{file_link}'">view paper</button>}
    </td>
    <td class="listitem-{line_no}">
      <button name="viewpaper" type="button" class="smallbutton" value="edit"
              onClick="self.location.href='{basepath}author_editpaper.php?paperid={paper_id}{&SID}'">
              edit</button>
    </td>
    <td class="listitem-{line_no}">&nbsp;</td>
  </tr>
