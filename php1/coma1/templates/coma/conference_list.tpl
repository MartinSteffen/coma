
{if1<p class="message">{message}</p>}

<table class="list">
  <tr class="listheader">
    <th class="listheader" colspan="3">Conference title</th>
    <th class="listheader">Date</th>
    <th class="listheader">Website</th>
  </tr>
  {lines}
  {if1
  <tr class="listitem-1">
    <td class="listitem-1" colspan="5">
      <span class="emph">There are no conferences yet.</span>
    </td>    
  </tr>
  }
</table>

<p class="message2">
  Login to the conference of your choice, if you already take part in it.<br>
  Otherwise, select the conference you would like to take part in and
  apply for a role as an author, or sign up for participating in it.<br>&nbsp;
</p>

<p class="message2">
  Do you want to <a href=2{basepath}create_conference.php{?SID}">create a new Conference?</a>
</p>
