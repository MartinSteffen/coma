 <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&SID}" class="link">{title}</a>
    </td>
    <td class="listitem-{line_no}">
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}" class="link">{author_name}</a>
    </td>
    <td class="listitem-{line_no}" colspan="2" nowrap>
      {if0<input type="radio" name="paper-{paper_id}" value="0" checked>
          <span class="attitude-none">no&nbsp;attitude</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="1">
          <span class="weak">prefer</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="2">
          <span class="weak">deny</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="3">
          <span class="weak">exclude</span>}
      {if1<input type="radio" name="paper-{paper_id}" value="0">
          <span class="weak">no&nbsp;attitude</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="1" checked>
          <span class="attitude-prefer">prefer</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="2">
          <span class="weak">deny</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="3">
          <span class="weak">exclude</span>}
      {if2<input type="radio" name="paper-{paper_id}" value="0">
          <span class="weak">no&nbsp;attitude</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="1">
          <span class="weak">prefer</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="2" checked>
          <span class="attitude-deny">deny</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="3">
          <span class="weak">exclude</span> &nbsp;&nbsp;&nbsp;}
      {if3<span class="attitude-exclude" align="center">excluded</span>
          <input type="hidden" name="paper-{paper_id}" value="3">}          
      <!--
      {if3<input type="radio" name="paper-{paper_id}" value="0">
          <span class="weak">no&nbsp;attitude</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="1">
          <span class="weak">prefer</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="2">
          <span class="weak">deny</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="paper-{paper_id}" value="3" checked>
          <span class="attitude-exclude">exclude</span>}
       -->
    </td>
  <!--
    <td class="listitem-{line_no}">
      {if0<span class="attitude-none">no&nbsp;attitude</span>}
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
  -->
  </tr>