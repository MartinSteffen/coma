<tr class="viewlines">
  <td class="viewline">
    <!--<a href='{basepath}chair_reviewdetails.php?reviewid={review_id}{&SID}'>-->
      <span class="emph">{reviewer_name}</span>
    <!--</a>-->
  </td>
  {rating_cols}
  <td class="viewline">
    <span class="emph">{total_rating}</span>
  </td>
  <td>
    <button name="viewreview" type="button" class="smallbutton" value="view details"
            onClick="self.location.href='{basepath}chair_reviewdetails.php?reviewid={review_id}{&SID}'">
            view details</button>    
  </td>
</tr>