<form action="{basepath}{targetpage}.php{?SID}" method="post" accept-charset="UTF-8">

<table class="viewtable">
  <tr class="viewheader">
    <th colspan="2" class="viewheader">Details of paper:</th>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Title:
    </td>
    <td class="viewline">{title}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Abstract:
    </td>
    <td class="viewline">{abstract}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Author:
    </td>
    <td class="viewline">
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}">{author_name}</a>
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Co-Authors:
    </td>
    <td class="viewline">{coauthors}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Version:
    </td>
    <td class="viewline">{version}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" width="15%">
      Last edited:
    </td>
    <td class="viewline">{last_edited}</td>
  </tr>  
  <tr class="viewline">
    <td class="viewline" width="15%">
      Topics:
    </td>
    <td class="viewline">{topics}</td>
  </tr>  
  <tr class="viewline">
    <td class="viewline" colspan="2">
      {if5<a href="{basepath}get_paper.php?paperid={paper_id}{&SID}">View paper</a>}
      {if6<span class="emph">There is no document available yet.</span>}
    </td>
  </tr>
</table>
</form>
