
{if9<p class="message">{message}</p>}

<table class="viewtable">
  <tr class="viewheader">
    <th class="viewheader" colspan="{cols}">Review report for '{title}' by {author_name}:</th>    
  </tr>
  <tr class="viewline">
    <td class="viewline" colspan="{cols}">The paper is currently being reviewed by
      <span class="emph">{reviews_num}</span> reviewers.
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">&nbsp;</td>
    {crit_cols}
    <td class="viewline">Total rating:</td>
    <td class="viewline">&nbsp;</td>
  </tr>
  {review_lines}
</table>

<p>&nbsp;</p>

Return to the <a href="{basepath}chair_papers.php{?SID}">paper overview</a>.