
<p class="message">
  It is <span class="emph">recommended</span> to proceed as follows:<br>
  Let CoMa make a <a href="{basepath}chair_distribution.php{?SID}" class="link">
  suggestion</a> (may take several seconds) of a possible paper/reviewer
  distribution for papers that are not assigned to the average number of
  reviewers yet. If the <a href="{basepath}chair_confconfig.php{?SID}" class="link">
  'Auto add reviewers' flag</a> is activated, CoMa will try to suggest more
  reviewers for that paper if possible. You may edit the suggested distribution
  and afterwards confirm it. After that, you can assign several papers by hand
  (this formular) or restart the suggestion process.
</p>

<table class="list">
  <tr class="listheader">
    <th class="listheader">Title</th>
    <th class="listheader">Author</th>
    <th class="listheader">Status</th>
    <th class="listheader">Reviews</th>
    <th class="listheader">Rating</th>
    <th class="listheader">Variance</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">Reviewers</th>
    <th class="listheader">Assigned reviewers</th>
   </tr>
   {lines}
</table>
