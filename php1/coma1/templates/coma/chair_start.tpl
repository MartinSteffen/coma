<table class="viewtable">
  <tr class="viewheader">
    <th class="viewheader">
      Welcome to CoMa - Your Conference Manager!
    </th>    
  </tr>
  <tr class="viewline">
    <td class="viewline">      
      As <span class="emph">chair</span> you can choose between the following tasks:
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">      
      Select <a href="{basepath}chair_users.php{?SID}" class="link">'Manage users'</a> to activate or delete user accounts,
      and to add and remove roles for single users.      
      {if1<br><span class="alert">There are {request_no} requests for accounts waiting for you to be accepted.</span>}
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">      
      Select <a href="{basepath}chair_papers.php{?SID}" class="link">'Manage papers'</a> to view or remove papers,
      or to change the current status of articles.<br>You can accept or reject papers or take part in
      the reviewing discussion of papers.
      {if2<br><span class="alert">New papers have been submitted since your last login.</span>}      
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">      
      Select <a href="{basepath}chair_reviews.php{?SID}" class="link">'Manage reviews'</a> to distribute papers to
      reviewers and to gather information about the reviewing process of papers.
      {if3<br><span class="alert">There are papers waiting for you to be distributed to reviewers.</span>}
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">      
      Select <a href="{basepath}chair_confconfig.php{?SID}" class="link">'Config. conference'</a> to edit the
      conference's parameters, e.g. the dates for the deadlines.
    </td>
  </tr>
</table>
