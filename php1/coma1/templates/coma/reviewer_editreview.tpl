
{if9<p class="message">{message}</p>}

<form action="{basepath}reviewer_editreview.php{?SID}" method="post" accept-charset="UTF-8">
  <input type="hidden" name="reviewid" value="{review_id}">
  <input type="hidden" name="action" value="submit">

<table class="formtable">
  <tr>
    <th>Review paper:</th>
    <td>{title} by {author_name}</td>
  </tr>
  <tr>
    <td colspan="2">      
      <table class="formlist" width="100%">
        <tr class="formlistheader">
          <th class="formlistheader">Rating criterion</th>
          <th class="formlistheader">Score</th>
          <th class="formlistheader">Comment</th>
        </tr>
        {crit_lines}
      </table>
    </td>  
  </tr>
  <tr> 
    <td><span class="emph">Overall rating:</span></td>
    <td>
      <span class="emph">{rating}%</span>
      <input type="submit" name="recalc" value="recalculate" class="smallbutton">
    </td>
  </tr>
  <tr>
    <td>
      Summary:
    </td>
    <td>
      <textarea name="summary" rows="3" cols="48">{summary}</textarea>
    </td>
  </tr>
  <tr>
    <td>
      Confidential Notes:
    </td>
    <td>
      <textarea name="confidential" rows="2" cols="48">{confidential}</textarea>
    </td>
  </tr>
  <tr>
    <td>
      Common remarks:
    </td>
    <td>
      <textarea name="remarks" rows="2" cols="48">{remarks}</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2">      
      <input type="submit" name="submit" value="Submit changes" class="button">
      <input type="reset" name="reset" value="Reset review" class="button">
    </td>
  </tr>
</table>
</form>

<p>&nbsp;</p>
