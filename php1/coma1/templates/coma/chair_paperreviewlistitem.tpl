<tr class="viewline">
  <td class="viewline">
    <!--<a href='{basepath}chair_reviewdetails.php?reviewid={review_id}{&SID}'>-->
      <span class="emph">{reviewer_name}:</span>
    <!--</a>-->
  </td>
  {rating_cols}
  <td class="viewline">
    <span class="emph">{total_rating}</span>
  </td>
  <td class="viewline">
    <form action="{basepath}chair_reviewdetails.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="reviewid" value="{review_id}">
      <input type="submit" name="view" value="view details" class="smallbutton">
    </form>      
  </td>
</tr>