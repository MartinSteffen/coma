  <tr>
    <td class="listitem-{line_no}">
      <a href="{basepath}user_userdetails.php?userid={rev_id}&amp;popup{&SID}" target="_blank" class="link">{rev_name}</a>
    </td>
    <td class="listitem-{line_no}">
      ({num_papers})
    </td>
    <td class="listitem-{line_no}">&nbsp;
      {if7<input type="checkbox" name="p{paper_id}r{rev_id}" value="1">}
      {if8<input type="checkbox" name="p{paper_id}r{rev_id}" value="1" checked="checked">}
    </td>
    <td class="listitem-{line_no}">&nbsp;
      {if0<span class="attitude-none">has no&nbsp;attitude</span>}
      {if1<span class="attitude-prefer">prefers paper</span>}
      {if2<span class="attitude-deny">denies paper</span>}
      {if3<span class="attitude-exclude">excluded</span>}
    </td>
    <td class="listitem-{line_no}">&nbsp;
      {topics}
    </td>
  </tr>
