<tr class="listitem-{line_no}">
  <td class="listitem-{line_no}" colspan="4">
    <a href="{basepath}main_conference_info.php?confid={confid}{&SID}" class="link">
      <span class="emph">{name}</span>
    </a>
  </td>
  <td class="listitem-{line_no}">{date}</td>
  <td>
    {if5 <a href="{link}" target="_blank"><img border="0" alt="visit website" height="20" width="64" src="{path}images/icon-website.gif"></a>}
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
  {if8
    <form action="{basepath}apply_role.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="hidden" name="roletype" value="{role_chair}">
      <input type="submit" name="submit" value="Apply as Chair" class="smallbutton">
    </form>
  }
  {if9
    <form action="{basepath}apply_role.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="hidden" name="retreat" value="retreat">
      <input type="hidden" name="roletype" value="{role_chair}">
      <input type="submit" name="submit" value="Retreat as Chair" class="smallbutton">
    </form>
  }
  </td>
  <td class="listitem-{line_no}">&nbsp;
  {if2
    <form action="{basepath}apply_role.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="hidden" name="roletype" value="{role_reviewer}">
      <input type="submit" name="submit" value="Apply as Reviewer" class="smallbutton">
    </form>
  }
  {if6
    <form action="{basepath}apply_role.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="hidden" name="retreat" value="retreat">
      <input type="hidden" name="roletype" value="{role_reviewer}">
      <input type="submit" name="submit" value="Retreat as Reviewer" class="smallbutton">
    </form>
  }
  </td>
  <td class="listitem-{line_no}">&nbsp;
  {if3
    <form action="{basepath}apply_role.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="hidden" name="roletype" value="{role_author}">
      <input type="submit" name="submit" value="Apply as Author" class="smallbutton">
    </form>
  }
  {if7
    <form action="{basepath}apply_role.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="hidden" name="retreat" value="retreat">
      <input type="hidden" name="roletype" value="{role_author}">
      <input type="submit" name="submit" value="Retreat as Author" class="smallbutton">
    </form>
  }
  </td>
  <td class="listitem-{line_no}" colspan="2">&nbsp;
  {if4
    <form action="{basepath}apply_role.php{?SID}" method="post" accept-charset="UTF-8">
      <input type="hidden" name="confid" value="{confid}">
      <input type="hidden" name="roletype" value="{role_participant}">
      <input type="submit" name="submit" value="Sign up as Participant" class="smallbutton">
    </form>
  }
  </td>
</tr>