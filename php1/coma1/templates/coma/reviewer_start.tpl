<table class="viewtable">
  <tr class="viewheader">
    <th class="viewheader">
      Welcome to CoMa - Your Conference Manager!
    </th>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      As <span class="emph">reviewer</span> you can choose between the following tasks:
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Select <a href="{basepath}reviewer_reviews.php{?SID}" class="link">'Manage my reviews'</a> to review the
      papers that have been distributed to you, or to edit the reviews you have made so far.<br>
      You can also take part in <a href="" class="link">reviewing discussions</a>.
      {if1<br><span class="alert">There are reviews missing that habe to be submitted until ...</span>}
      {if2<br><span class="alert">New paper discussion have been started since your last login.</span>}
      {if3<br><span class="alert">The reviewing of papers has ended.</span>}
    </td>
  </tr>
  <tr class="viewline">
    <td class="viewline">
      Select <a href="{basepath}reviewer_prefers.php{?SID}" class="link">'Manage my preferences'</a> to submit
      your attitudes towards certain papers or topics. Go here if you favor to review certain topics or papers
      or if you don't want to review certain papers.
      {if4<br><span class="alert">Papers will be distributed soon. You should submit your preferences soon.</span>}
    </td>
  </tr>
</table>
