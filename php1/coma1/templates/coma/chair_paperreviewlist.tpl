
{if9<p class="message">{message}</p>}

<table class="viewtable">
  <tr class="viewheader">
    <th class="viewheader"colspan="4">Review report for '{title}' by {author_name}:</th>     
  </tr>
  <tr class="viewheader">
    <th class="viewheader">&nbsp;</th>   
    {crit_cols}
    <th class="viewheader">Total rating:</th>
  </tr>
  {review_lines}
</table>

<p>&nbsp;</p>

Return to the <a href="{basepath}chair_papers.php{?SID}">paper overview</a>.