 <tr class="listitem-{line_no}"> 
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={topic_id}{&SID}">{topic}</a>
    </td> 
    <td class="listitem-{line_no}">&nbsp;</td>
    <td class="listitem-{line_no}">
      {if0<span class="status-noattitude">no attitude</span>}
      {if1<span class="status-prefer">prefer</span>}
      {if2<span class="status-deny">deny</span>}
      {if3<span class="status-exclude">exclude</span>}
    </td>    
    <td class="listitem-{line_no}">
      <select name="topic-{topic_id}" size="3">
        <option>prefer</option>
        <option>no attitude</option>        
      </select>      
    </td>
  </tr>