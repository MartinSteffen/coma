
<p class="message2">
  <span class="emph">Note:</span> A reviewer's name in one of the following
    colors means:<br><br>
  <span class="diststatus-0">The paper is already assigned to the reviewer.</span><br>
  <span class="diststatus-2">The reviewer neither explicitly wants to review the
    paper nor prefers the topic of the paper.</span><br>
  <span class="diststatus-3">The reviewer prefers the topic of the paper.</span><br>
  <span class="diststatus-4">The reviewer explicitly wants to review the paper.
    </span><br><br>
  For each paper, you may unselect some of the suggested reviewers except he/she
    is already assigned.<br>
  Finally, you can either confirm or reject the distribution.
</p>

<form action="{basepath}chair_distribution.php{?SID}" method="post" accept-charset="UTF-8">
<table class="list">
  <tr class="listheader">
    <th class="listheader">Title</th> 
    <th class="listheader">Author</th> 
    <th class="listheader">Suggested reviewers</th>
  </tr>
  {lines}
</table>
<input type="submit" name="confirm" value="Confirm distribution (obligatory!)" class="button">
<input type="reset" name="dismiss" value="Dismiss distribution" class="button">
</form>

<button name="dismissReviewer" type="button" class="smallbutton" value="dismiss"
  onClick="self.location.href='{basepath}chair_distribution.php?paperid={paper_id}&reviewerid={rev_id}{&SID}'">
  dismiss</button>