
{if9<p class="message">{message}</p>}

<table class="viewtable">
  <tr class="viewheader">
    <th class="viewheader">Review report for '{title}' by {author_name}:</th>    
  </tr>
  <tr class="viewline">
    <td class="viewline">The paper is currently being reviewed by
      <span class="emph">{reviewers_num}</span> reviewers.<br>
      <span class="emph">{reviews_num}</span> reviews have been submitted already.<br>
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
  
<table class="subviewtable">  
  <tr class="viewheader">
    <th class="viewheader">&nbsp;</th>
    {crit_cols}
    <th class="viewheader">Total:</th>
    <th class="viewheader">&nbsp;</th>
  </tr>
  {review_lines}
  <tr class="viewline">
    <td class="viewline">
      <span class="emph">Average rating:</span>
    </td>
    <td class="viewline" colspan="{cols}">&nbsp;</td>
    <td class="viewline">
      <span class="emph">{avg_rating}</span>
    </td>
    <td class="viewline">&nbsp;</td>
  </tr>  
</table>

    </td>
  </tr>  
</table>

<p>&nbsp;</p>

Return to the <a href="{basepath}chair_papers.php{?SID}" class="link">paper overview</a>.