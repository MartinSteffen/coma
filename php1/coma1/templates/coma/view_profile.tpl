<table class="viewtable">
  <tr class="viewheader">
    <th colspan="2" class="viewheader">User Profile:</th>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Name:
    </td>
    <td class="viewline">
      {name_title} {first_name} {last_name}
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      E-mail address:
    </td>
    <td class="viewline">
      <a href="{email_link}" class="email">{email}</a>
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Affiliation:
    </td>
    <td class="viewline">
      {affiliation}
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Phone number:
    </td>
    <td class="viewline">
      {phone}
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Fax number:
    </td>
    <td class="viewline">
      {fax}
    </td>
  </tr>
</table>

{author_papers}

<p>&nbsp;</p>

<p class="message">
  {if1 Click here to <a href="{basepath}chair_prefers.php?userid={userid}{&SID}" class="link">show
  reviewing preferences</a> for this reviewer.<br>&nbsp;<br>}
  {navlinkBACK Return to the <a href="javascript:history.back()" class="link">last page</a>.}
  {navlinkCLOSE <a href="javascript:close()" class="link">Close this page</a>.}
</p>