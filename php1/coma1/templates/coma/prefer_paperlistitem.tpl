 <tr class="listitem-{line_no}"> 
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&SID}">{title}</a>
    </td> 
    <td class="listitem-{line_no}"> 
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}">{author_name}</a>
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="status-noattitude">no attitude</span>}
      {if1<span class="status-prefer">prefer</span>}
      {if2<span class="status-deny">deny</span>}
      {if3<span class="status-exclude">exclude</span>}
    </td>    
    <td class="listitem-{line_no}">
      <select name="paper-{paper_id}" size="3">
        <option>prefer</option>
        <option>deny</option>
        <option>exclude</option>
        <option>no attitude</option>        
      </select>      
    </td>
  </tr>