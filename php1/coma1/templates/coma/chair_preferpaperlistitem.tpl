 <tr class="listitem-{line_no}"> 
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&SID}" class="link">{title}</a>
    </td> 
    <td class="listitem-{line_no}"> 
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}" class="link">{author_name}</a>
    </td>
    <td class="listitem-{line_no}" colspan="2">
      {if0<span class="attitude-none">no attitude</span> &nbsp;&nbsp;&nbsp;
          <span class="weak">prefer</span> &nbsp;&nbsp;&nbsp;          
          <span class="weak">deny</span> &nbsp;&nbsp;&nbsp;          
          <span class="weak">exclude</span>}
      {if1<span class="weak">no attitude</span> &nbsp;&nbsp;&nbsp;
          <span class="attitude-prefer">prefer</span> &nbsp;&nbsp;&nbsp;
          <span class="weak">deny</span> &nbsp;&nbsp;&nbsp;          
          <span class="weak">exclude</span>}
      {if2<span class="weak">no attitude</span> &nbsp;&nbsp;&nbsp;
          <span class="weak">prefer</span> &nbsp;&nbsp;&nbsp;
          <span class="attitude-deny">deny</span> &nbsp;&nbsp;&nbsp;
          <span class="weak">exclude</span> &nbsp;&nbsp;&nbsp;}
      {if3<span class="weak">no attitude</span> &nbsp;&nbsp;&nbsp;
          <span class="weak">prefer</span> &nbsp;&nbsp;&nbsp;
          <span class="weak">deny</span> &nbsp;&nbsp;&nbsp;
          <span class="attitude-exclude">exclude</span>}
    </td>
  </tr>