 <tr class="listitem-{line_no}"> 
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&SID}">{title}</a>
    </td> 
    <td class="listitem-{line_no}"> 
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}">{author_name}</a>
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="attitude-none">no attitude</span>}
      {if1<span class="attitude-prefer">prefer</span>}
      {if2<span class="attitude-deny">deny</span>}
      {if3<span class="attitude-exclude">exclude</span>}
    </td>    
    <td class="listitem-{line_no}">
      <select name="paper-{paper_id}" size="1">
        <option>prefer</option>
        <option>deny</option>
        <option>exclude</option>
        <option>no attitude</option>        
      </select>      
    </td>
  </tr>