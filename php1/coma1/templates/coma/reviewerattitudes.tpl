  <tr>
    <td class="listitem-{line_no}">
      <a href="{basepath}user_userdetails.php?userid={rev_id}{&SID}" class="link">{rev_name}</a>
    </td>
    <td class="listitem-{line_no}">
      <input type="checkbox" name="p{paper_id}r{rev_id}" value="1" checked="checked">
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="attitude-none">No&nbsp;attitude</span>}
      {if1<span class="attitude-prefer">Prefers paper</span>}
      {if2<span class="attitude-deny">Denies paper</span>}
      {if3<span class="attitude-exclude" align="center">Paper excluded</span>}
    </td>
  </tr>
