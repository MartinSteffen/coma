<tr class="listitem-{line_no}">
  <td class="listitem-{line_no}" colspan="3">
    <a href="{basepath}main_conference_info.php?confid={confid}{&SID}">
      <span class="emph">{name}</span>
    </a>
  </td>
  <td class="listitem-{line_no}">{date}</td>
  <td>
    {if5 <a href="{link}"><img border="0" alt="website" height="20" width="64"
      src="{path}images/icon_website.gif"></a>}
  </td>
</tr>
<tr class="listitem-{line_no}">
  <td class="listitem-{line_no}">&nbsp;
  {if1
    <form action="{basepath}login_conference.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="submit" name="submit" value="Login" class="button">
    </form>
  }
  </td>
  <td class="listitem-{line_no}">&nbsp;
  {if2
    <form action="{basepath}apply_reviewer.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="submit" name="submit" value="Apply as Reviewer" class="button">
    </form>
  }
  </td>
  <td class="listitem-{line_no}">&nbsp;
  {if3
    <form action="{basepath}apply_author.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="submit" name="submit" value="Apply as Author" class="button">
    </form>
  }
  </td>
  <td class="listitem-{line_no}" colspan="2">&nbsp;
  {if4
    <form action="{basepath}apply_participant.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="submit" name="submit" value="Sign up as Participant" class="button">
    </form>
  }
  </td>    
</tr>