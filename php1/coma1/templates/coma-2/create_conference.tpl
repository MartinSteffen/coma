
{if1<p class="message">{message}</p>}


<form action="{basepath}create_conference.php?{SID}" method="post">

<table class="formtable">
  <tr>
    <th colspan="2">Enter conference data:</th>
  </tr>
  <tr>
    <td> 
      Title:
    </td>
    <td>      
      <input type="text" name="name" size="32" maxlength="127" value="{name}" /> *
    </td>
  </tr>
  <tr>
    <td> 
      Description:
    </td>
    <td>      
      <input type="text" name="description" size="48" value="{description}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Homepage URL:
    </td>
    <td>      
      <input type="text" name="homepage" size="32" maxlength="127" value="{homepage}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Starts:
    </td>
    <td>      
      <input type="text" name="start_date" size="16" maxlength="20" value="{start_date}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      End:
    </td>
    <td>      
      <input type="text" name="end_date" size="16" maxlength="20" value="{end_date}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Deadline for abstracts:
    </td>
    <td>      
      <input type="text" name="abstract_dl" size="16" maxlength="20" value="{abstract_dl}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Deadline for paper submission:
    </td>
    <td>      
      <input type="text" name="paper_dl" size="16" maxlength="20" value="{paper_dl}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Deadline for reviews:
    </td>
    <td>      
      <input type="text" name="review_dl" size="16" maxlength="20" value="{review_dl}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Deadline for final versions:
    </td>
    <td>      
      <input type="text" name="final_dl" size="16" maxlength="20" value="{final_dl}"/ >
    </td>
  </tr>
  <tr>
    <td> 
      Date for notification:
    </td>
    <td>      
      <input type="text" name="notification" size="16" maxlength="20" value="{notification}"/ >
    </td>
  </tr>

  <tr>
    <td colspan="2">
      <input type="hidden" name="min_reviews" value="{min_reviews}" />
      <input type="hidden" name="def_reviews" value="{def_reviews}" />
      <input type="hidden" name="min_papers" value="{min_papers}" />
      <input type="hidden" name="max_papers" value="{max_papers}" />
      <input type="hidden" name="variance" value="{variance}" />    
      <input type="hidden" name="criteria" value="{criteria}" />    
      <input type="hidden" name="topics" value="{topics}" />    
      <input type="hidden" name="crit_max" value="{crit_max}" />
      <input type="hidden" name="crit_descr" value="{crit_descr}" />
      <input type="hidden" name="auto_actaccount" value="{auto_actaccount}" />
      <input type="hidden" name="auto_paperforum" value="{auto_paperforum}" />
      <input type="hidden" name="auto_addreviewer" value="{auto_addreviewer}" />
      <input type="hidden" name="auto_numreviewer" value="{auto_numreviewer}" />
            
      <input type="hidden" name="action" value="submit" />
      <input type="submit" name="submit" value="Create conference" class="button" />
      <form action="{basepath}create_conference.php?{SID}" method="post">
        <input type="hidden" name="action" value="advanced_config" />
        <input type="submit" name="submit" value="Advanced settings" class="button" />
      </form>
    </td>
  </tr>
</table>
</form>

<form action="{basepath}main_conferences.php?{SID}" method="post">
  <input type="submit" name="cancel" value="Cancel" class="button" />
</form>
