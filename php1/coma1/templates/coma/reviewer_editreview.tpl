
{if1<p class="message">{message}</p>}

<form action="{basepath}reviewer_editreview.php{?SID}" method="post">

<table class="formtable">
  <tr>
    <th colspan="2">Review paper 'Increasing Shitimonial' by Robby Rabbit:</th>
  </tr>
  <tr>
    <td colspan="2">
      <table class="formlist">
        <tr class="formlistheader">
          <th class="formlistheader">>Rating criterion</th>
          <th class="formlistheader">>Score</th>
          <th class="formlistheader">>Comment</th>
        </tr>
        <tr class="formlistitem"> 
          <td class="formlistitem">Truth:</td>
          <td class="formlistitem"><input type="text" size="2" name="rating-1" value="2"/>/3</td>
          <td class="formlistitem"><textarea name="comment-1" rows="1" cols="48">Bla, bla...</textarea></td>
        </tr>
        <tr class="formlistitem">
          <td class="formlistitem">Originality:</td>
          <td class="formlistitem"><input type="text" size="2" name="rating-2" value="1"/>/5</td>
          <td class="formlistitem"><textarea name="comment-2" rows="1" cols="48">Bla, bla...</textarea></td>
        </tr>
        <tr class="formlistitem">
          <td class="formlistitem">Style:</td>
          <td class="formlistitem"><input type="text" size="2" name="rating-3" value="7"/>/10</td>
          <td class="formlistitem"><textarea name="comment-3" rows="1" cols="48">Bla, bla...</textarea></td>
        </tr>        
      </table>
    </td>  
  </tr>
  <tr> 
    <td><span class="emph">Overall rating:</span></td>
    <td><input type="text" size="2" name="overall rating" value="3"/>/5</td>          
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
      <textarea name="summary" rows="2" cols="48">Very shity, especially in the details.</textarea>
    </td>
  </tr>
  <tr>
    <td>
      Common remarks:
    </td>
    <td>
      <textarea name="summary" rows="2" cols="48">There's nothing left to say.</textarea>
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

