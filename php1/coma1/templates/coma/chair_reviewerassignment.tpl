
{if9<p class="message-failed">{message}</p>}

<p class="message">
  ...
</p>

<form action="{basepath}{targetpage}{?SID}" method="post" accept-charset="UTF-8">
  <input type="hidden" name="action" value="submit">

<table class="list">
  <tr class="listheader">
    <th class="listheader" colspan="2">Available reviewers:</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">Preferred topics</th>
  </tr>
  {reviewer_lines}
  <tr>
    <td colspan="2">
      <input type="submit" name="submit" value="Accept changes" class="button">
      <input type="reset"  name="reset" value="Reset settings" class="button">
    </td>
  </tr>
</table>

</form>

<p>&nbsp;</p>