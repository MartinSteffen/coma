
<p class="message">
  It is <span class="emph">recommended</span> to proceed as follows:<br>
  Let CoMa make a <a href="{basepath}chair_distribution.php{?SID}" class="link">
  suggestion</a> (may take several seconds) of a possible paper/reviewer
  distribution for papers that are not assigned to the default number of
  reviewers yet.<br>
  If the 
  'Auto add reviewers' flag is activated, CoMa will try to suggest n+m
  reviewers for ambiguous papers if possible, where n ist the default number of
  reviewers per paper and m is the number of additional reviewers.
  (See <a href="{basepath}chair_confconfig.php{?SID}" class="link">Conference
  Config</a> for more information.)<br>
  You may edit the suggested distribution and afterwards confirm it.<br>
  Later, you can assign several papers by hand (this formular) or restart the
  suggestion process.
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
