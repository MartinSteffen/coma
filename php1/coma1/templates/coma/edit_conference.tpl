
{if9<p class="message-failed">{message}</p>}

<form action="{basepath}chair_confconfig.php{?SID}" method="post" accept-charset="UTF-8">

<table class="formtable">
  <tr>
    <th colspan="2">Enter conference data:</th>
  </tr>
  <tr>
    <td>
      <span class="red">*</span>Title:
    </td>
    <td>
      <input type="text" name="name" size="48" maxlength="127" value="{name}">
    </td>
  </tr>
  <tr>
    <td>
      Description:
    </td>
    <td>
      <textarea name="description" rows="4" cols="48">{description}</textarea>
    </td>
  </tr>
  <tr>
    <td>
      Homepage URL:
    </td>
    <td>
      <input type="text" name="homepage" size="48" maxlength="127" value="{homepage}">
    </td>
  </tr>
  <tr>
    <td>
      <span class="red">*</span>Starts:
    </td>
    <td>
      <input type="text" name="start_date" size="16" maxlength="16" value="{start_date}">
    </td>
  </tr>
  <tr>
    <td>
      End:
    </td>
    <td>
      <input type="text" name="end_date" size="16" maxlength="16" value="{end_date}">
    </td>
  </tr>
  <tr>
    <td>
      <span class="red">*</span>Deadline for abstracts:
    </td>
    <td>
      <input type="text" name="abstract_dl" size="16" maxlength="16" value="{abstract_dl}">
    </td>
  </tr>
  <tr>
    <td>
      <span class="red">*</span>Deadline for paper submission:
    </td>
    <td>
      <input type="text" name="paper_dl" size="16" maxlength="16" value="{paper_dl}">
    </td>
  </tr>
  <tr>
    <td>
      <span class="red">*</span>Deadline for reviews:
    </td>
    <td>
      <input type="text" name="review_dl" size="16" maxlength="16" value="{review_dl}">
    </td>
  </tr>
  <tr>
    <td>
      <span class="red">*</span>Deadline for final versions:
    </td>
    <td>
      <input type="text" name="final_dl" size="16" maxlength="16" value="{final_dl}">
    </td>
  </tr>
  <tr>
    <td>
      Date for notification:
    </td>
    <td>
      <input type="text" name="notification" size="16" maxlength="16" value="{notification}">
    </td>
  </tr>

  <tr>
    <td colspan="2">
      <input type="hidden" name="min_reviews" value="{min_reviews}">
      <input type="hidden" name="def_reviews" value="{def_reviews}">
      <input type="hidden" name="min_papers" value="{min_papers}">
      <input type="hidden" name="max_papers" value="{max_papers}">
      <input type="hidden" name="variance" value="{variance}">
      <input type="hidden" name="auto_actaccount" value="{if2 1}">
      <input type="hidden" name="auto_paperforum" value="{if3 1}">
      <input type="hidden" name="auto_addreviewer" value="{if4 1}">
      <input type="hidden" name="auto_numreviewer" value="{auto_numreviewer}">

      <input type="hidden" name="action" value="submit">
      <input type="submit" name="submit" value="Submit Changes" class="button">
      <input type="submit" name="adv_config" value="Advanced settings" class="button">
    </td>
  </tr>
</table>

<table class="formtable">
  <tr>
    <td>Delete this conference?
      <input type="checkbox" name="confirm_delete" value="1">
    </td>
    <td>
      <input type="submit" name="delete" value="Delete" class="button">
    </td>
  </tr>
</table>
</form>

<p class="message">
  Dates should be entered in common U.S. writing <b>mm/dd/yyyy</b>! eg <b>01/15/2005</b>.<br>
  But lots of other formats are accepted, too. (eg <b>Jan 15, 2005</b>)<br>
  Hint: You can also use stuff like <b>today</b>, <b>1 year</b> and so on...
</p>
