
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
    <td class="viewline"><span class="emph">Total:</span></td>
    <td class="viewline">&nbsp;</td>
  </tr>
  {review_lines}
  <tr class="viewline">
    <td class="viewline">&nbsp;</td>
    <td class="viewline" colspan="{crit_cols}">&nbsp;</td>
    <td class="viewline">
      <span class="emph">{avg_rating}</span>
    </td>
    <td class="viewline">&nbsp;</td>
  </tr>  
</table>

<p>&nbsp;</p>

Return to the <a href="{basepath}chair_papers.php{?SID}">paper overview</a>.