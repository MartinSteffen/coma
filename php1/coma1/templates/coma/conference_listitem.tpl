<tr class="listitem-{line_no}">
  <td class="listitem-{line_no}" colspan="3">
    <a href="{basepath}main_conference_info.php?confid={confid}&{SID}">
      <span class="emph">{name}</span>
    </a>{if4 &nbsp; | &nbsp; <a href="{link}">homepage</a>}
  </td>
  <td class="listitem-{line_no}">
    {startdate} - {enddate}
  </td>
</tr>
<tr class="listitem-{line_no}">
  <td class="listitem-{line_no}">&nbsp;
  {if1
    <form action="{basepath}login_conference.php?{SID}" method="post">
      <input type="hidden" name="confid" value="{confid}"></input>
      <input type="submit" name="submit" value="Login" class="button"></input>
    </form>
  }
  </td>
  <td class="listitem-{line_no}">&nbsp;
  {if2
    <form action="{basepath}apply_author.php?{SID}" method="post">
      <input type="hidden" name="confid" value="{confid}"></input>
      <input type="submit" name="submit" value="Apply as Author" class="button"></input>
    </form>
  }
  </td>
  <td class="listitem-{line_no}">&nbsp;
  {if3
    <form action="{basepath}apply_participant.php?{SID}" method="post">
      <input type="hidden" name="confid" value="{confid}"></input>
      <input type="submit" name="submit" value="Sign up as Participant" class="button"></input>
    </form>
  }
  </td>
  <td>&nbsp;</td>
</tr>