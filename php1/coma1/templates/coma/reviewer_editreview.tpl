
{if1<p class="message">{message}</p>}

<form action="{basepath}reviewer_editreview.php{?SID}" method="post">
  <input type="hidden" name="paperid" value="{paperid}" />
  <input type="hidden" name="action" value="submit" />

<table class="list">
  <tr class="listheader">
    <th width="35%" class="listheader">Paper</th> 
    <th width="20%" class="listheader">Author</th> 
    <th width="10%" class="listheader">Status</th>
    <th width="10%" class="listheader">My rating</th>
    <th width="10%" class="listheader">Total rating</th>
    <th width="15%" class="listheader">Discussion</th>    
   </tr>

  <tr class="listitem-1"> 
    <td class="listitem-1"> 
      <a href="{basepath}reviewer_editreview.php?reviewid={reviewid}{&SID}">
      Neueste Rezepte</a>
    </td> 
    <td class="listitem-1"> 
      Robby Rabbit  
    </td>
    <td class="listitem-1"> 
      <span class="status-reviewed">reviewed</span>
    </td>
    <td class="listitem-1"> 5/5 </td>
    <td class="listitem-1"> 4.2/5 </td>
    <td class="listitem-1">  
      <form action="" method="post">
        <input type="hidden" name="confid" value="{confid}" />
        <input type="submit" name="submit" value="enter" class="button" />
      </form> 
    </td>   
    <td>      
      <input type="submit" name="submit" value="View" class="button" />
    </td>
  </tr>
  <tr>
    <td colspan="6">
    Description: This paper is about serious infections of teenage angst.
    </td>
  <tr>
</table>
</form>

<form action="{basepath}reviewer_editreview.php{?SID}" method="post">
  <input type="hidden" name="paperid" value="{paperid}" />
  <input type="hidden" name="action" value="submit" />

<table class="formtable">
  <tr>
    <th colspan="2">Review this paper</th>
  </tr>
  <tr>
    <td colspan="2">      
        <tr class="formlistheader">
          <th class="formlistheader">Rating criterion</th>
          <th class="formlistheader">Score</th>
          <th class="formlistheader">Comment</th>
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
      <input type="submit" name="submit" value="Submit changes" class="button" />
      <input type="reset" name="reset" value="Reset review" class="button" />    
    </td>
  </tr>
</table>
</form>

<form action="{basepath}reviewer_reviews.php{?SID}" method="post">
  <input type="submit" name="cancel" value="Cancel" class="button" />
</form>

