
{if9<p class="message">{message}</p>}

<table class="viewtable">
  <tr class="viewheader">
    <th class="viewheader" colspan="{cols}">Review report for '{title}' by {author_name}:</th>    
  </tr>
  <tr class="viewline">
    <td class="viewline" colspan="{cols}">The papers is currently being reviewed by
      <span class="emph">{reviews_num}</span> reviewers.
    </td>
  </tr>
  <tr class="viewline">
    <tr class="viewline">&nbsp;</tr>
    {crit_cols}
    <tr class="viewline">Total rating:</tr>
    <tr class="viewline">&nbsp;</tr>
  </tr>
  {review_lines}
</table>

<p>&nbsp;</p>

Return to the <a href="{basepath}chair_papers.php{?SID}">paper overview</a>.