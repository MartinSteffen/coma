
{if1<p class="message">{message}</p>}

<table class="list">
  <tr class="listheader">
    <th class="listheader" colspan="3">Conference title</th>
    <th class="listheader">Date</th>
  </tr>
  {lines}
  {if1
  <tr class="listitem-1" colspan="3">
    <td class="listitem-1">
      <span class="emph">There are no conferences yet.</span>
    </td>
    <td class="listitem-1">&nbsp;</td>
  </tr>
  }
</table>

<p class="message2">
  Login to the conference of your choice, if you already take part in it.<br>
  Otherwise, select the conference you would like to take part in and
  apply for a role as an author, or sign up for participating in it.<br>&nbsp;
</p>

<p class="message2">
  If you want to create a new conference, please use the button below:<br>
</p>

<form action="{basepath}create_conference.php?{SID}" method="post">
  <input type="submit" name="submit" value="Create new conference" class="button" />
</form>
