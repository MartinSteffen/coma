<form action="{basepath}{targetpage}.php{?SID}" method="post" accept-charset="UTF-8">

<table class="viewtable">
  <tr class="viewheader">
    <th colspan="2" class="viewheader">Details of paper:</th>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Title:
    </td>
    <td class="viewline">{title}&nbsp;</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Abstract:
    </td>
    <td class="viewline">{abstract}&nbsp;</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Author:
    </td>
    <td class="viewline">
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}" class="link">{author_name}</a>&nbsp;
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Co-Authors:
    </td>
    <td class="viewline">{coauthors}&nbsp;</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Version:
    </td>
    <td class="viewline">{version}&nbsp;</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Last edited:
    </td>
    <td class="viewline">{last_edited}&nbsp;</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Topics:
    </td>
    <td class="viewline">{topics}&nbsp;</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" colspan="2">
      {if5<a href="{basepath}get_paper.php?paperid={paper_id}{&SID}" class="buttonlink">View paper</a>}
      {if6<span class="emph">There is no document available yet.</span>}
      &nbsp;
    </td>
  </tr>
</table>
</form>

{if7
<p class="message">
  This paper has been accepted for the conference.
</p>
}
<p>&nbsp;</p>

<p class="message">
  {navlinkBACK Return to the <a href="javascript:history.back()" class="link">last page</a>.}
  {navlinkCLOSE <a href="javascript:close()" class="link">Close this page</a>.}
</p>
