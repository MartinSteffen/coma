
{if1<p class="message">{message}</p>}

<form action="{basepath}{targetpage}.php{?SID}" method="post" accept-charset="UTF-8">

<table class="viewtable">
  <tr class="viewheader">
    <th colspan="2" class="viewheader">Details of paper:</th>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Title:
    </td>
    <td class="viewline">{title}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Abstract:
    </td>
    <td class="viewline">{abstract}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Author:
    </td>
    <td class="viewline">
      <a href="{basepath}user_persondetails.php?personid={author_id}{&SID}">{author_name}</a>
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Co-Authors:
    </td>
    <td class="viewline">{coauthors}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Version:
    </td>
    <td class="viewline">{version}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Last edited:
    </td>
    <td class="viewline">{last_edited}</td>
  </tr>  
  <tr class="viewline">
    <td class="viewline" colspan="2">
      {if5<a href="{file_link}">view paper</a>}
      {if6<span class="emph">There is no document available yet.</span>}
    </td>
  </tr>
</table>
</form>

<p>&nbsp;</p>
