
{if1<p class="message">{message}</p>}

<form action="{basepath}reviewer_editreview.php{?SID}" method="post">

<table class="formtable">
  <tr>
    <th colspan="2">Review paper 'Increasing Shitimonial' by Robby Rabbit:</th>
  </tr>
  <tr>
    <td colspan="2">
      <table class="list">
        <tr class="listheader">
           <th>Evaluation of review criteria:</th>
        </tr>
        <tr class="listitem-1"> 
          <td class="listitem-1">truth:</td>
          <td class="listitem-1"><input type="text" size="2" name="overall rating" value="2"/>/3</td>
        </tr>
        <tr class="listitem-2"> 
          <td class="listitem-2">originality:</td>
          <td class="listitem-2"><input type="text" size="2" name="overall rating" value="1"/>/5</td>
        </tr>
        <tr class="listitem-1"> 
          <td class="listitem-1">style:</td>
          <td class="listitem-1"><input type="text" size="2" name="overall rating" value="7"/>/10</td>
        </tr>
        <tr class="listitem-2"> 
          <td class="listitem-2"><span class="emph">overall rating:</span></td>
          <td class="listitem-2"><input type="text" size="2" name="overall rating" value="3"/>/5</td>
        </tr>
      </table>
    </td>  
  </tr>
  <tr>
    <td>
      Summary:
    </td>
    <td>
      <textarea name="summary" rows="3" cols="48">I don't know how to rate, I threw dice.</textarea>
    </td>
  </tr>
  <tr>
    <td>
      Confidential Notes:
    </td>
    <td>
      <textarea name="summary" rows="3" cols="48">Very shity, especially in the details.</textarea>
    </td>
  </tr>
  <tr>
    <td>
      Common remarks:
    </td>
    <td>
      <textarea name="summary" rows="3" cols="48">There's nothing left to say.</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="hidden" name="action" value="submit" />
      <input type="submit" name="submit" value="Submit changes" class="button" />
      <input type="reset" name="reset" value="Reset review" class="button" />
    </td>
  </tr>
</table>

<form action="{basepath}reviewer_reviews.php{?SID}" method="post">
  <input type="submit" name="cancel" value="Cancel" class="button" />
</form>

