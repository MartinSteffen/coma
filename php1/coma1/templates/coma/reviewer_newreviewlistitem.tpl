  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      {if6<a href="{basepath}reviewer_createreview.php?paperid={paper_id}{&SID}" class="link">}
      {title}{if6</a>}
    </td>
    <td class="listitem-{line_no}">
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}" class="link">{author_name}</a>
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="status-unreviewed">unreviewed</span>}
      {if1<span class="status-reviewed">reviewed</span>}
      {if2<span class="status-critical">critical</span>}
      {if3<span class="status-accepted">accepted</span>}
      {if4<span class="status-rejected">rejected</span>}
    </td>
    <td class="listitem-{line_no}" colspan="2">
      <span class="status-unreviewed">not rated yet</span>
    </td>
  </tr>
  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}" colspan="5">
      <table width="100%">
        <tr>
          <td>
        {if6<a href="{basepath}reviewer_createreview.php?paperid={paper_id}{&SID}"
               class="buttonlink">Create review</a>}&nbsp;
          </td>
          <td>
            <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&SID}"
               class="buttonlink">See paper details</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>