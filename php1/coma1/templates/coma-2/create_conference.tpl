
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
      <input type="text" name="name" size="32" maxlength="127" value="{name}" />
    </td>
  </tr>
  <tr>
    <td> 
      Description:
    </td>
    <td>      
      <input type="text" name="description" size="48" value="{description}"/ > *
    </td>
  </tr>
  <tr>
    <td> 
      Homepage URL:
    </td>
    <td>      
      <input type="text" name="homepage" size="32" maxlength="127" value="{homepage}"/ > *
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
    <td colspan="2">
      <input type="hidden" name="action" value="submit" />
      <input type="submit" name="submit" value="Create conference" class="button" />
      <form action="{basepath}create_conference_ext.php?{SID}" method="post">
        <input type="submit" name="submit" value="Advanced settings" class="button" />
      </form>
    </td>
  </tr>
</table>
</form>

<form action="{basepath}main_conferences.php?{SID}" method="post">
  <input type="submit" name="cancel" value="Cancel" class="button" />
</form>
