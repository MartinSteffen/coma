<table class="viewtable">
  <tr class="viewheader">
    <th class="viewheader">
      Welcome to CoMa - Your Conference Manager!
    </th>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      As <span class="emph">author</span> you can choose between the following tasks:
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Select <a href="{basepath}author_papers.php{?SID}" class="link">'Manage my papers'</a> to edit your
      papers{if0, <a href="{basepath}author_createpaper.php{?SID}" class="link">add new papers</a>}{if1, or upload documents to existing papers}.      
      {if2<br><span class="alert">Be aware that new papers can only be submitted until {abstract_dl}.</span>}
      {if3<br><span class="alert">There are still documents missing that have to be uploaded until {paper_dl}.</span>}
      {if4<br><span class="alert">Be aware that the final paper versions have to be submitted until {final_dl}.</span>}
      {if5<br><span class="alert">There are papers of yours that have been accepted.</span>}
    </td>
  </tr>
</table>
