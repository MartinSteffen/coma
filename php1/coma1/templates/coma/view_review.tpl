
{if9<p class="message">{message}</p>}

<table class="viewtable">
  <tr>
    <th class="viewheader">Review details of paper:</th>
    <td class="viewheader">{title} by {author_name}</td>
  </tr>
  <tr class="viewline"> 
    <td class="viewline">
      <span class="emph">Reviewer:</span>
    </td>
    <td class="viewline">
      <span class="emph">{reviewer_name}</span>      
    </td>
  </tr>
  <tr class="viewline">
    <td colspan="2" class="viewline">
      <table class="viewlist" width="100%">
        <tr class="viewheader">
          <th class="viewline">Rating criterion</th>
          <th class="viewline">Score</th>
          <th class="viewline">Comment</th>
        </tr>
        {crit_lines}
      </table>
    </td>  
  </tr>
  <tr class="viewline"> 
    <td class="viewline">
      <span class="emph">Overall rating:</span>
    </td>
    <td class="viewline">
      <span class="emph">{rating}</span>      
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Summary:
    </td>
    <td class="viewline">
      {summary}
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Confidential Notes:
    </td>
    <td class="viewline">
      {confidential}
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Common remarks:
    </td>
    <td class="viewline">
      {remarks}
    </td>
  </tr>
  <tr class="viewline"> 
    <td class="viewline">
      <span class="emph">Currently average rating:</span>
    </td>
    <td class="viewline">
      <span class="emph">{avg_rating}</span><br>
      {if1<span class="alert">This value is critical because the single reviews of this
          paper are highly different!</span>}
    </td>
  </tr>
</table>
</form>

<p>&nbsp;</p>

Return to the <a href="{basepath}chair_paperreview.php?paperid={paper_id}{&SID}">paper review report</a>.