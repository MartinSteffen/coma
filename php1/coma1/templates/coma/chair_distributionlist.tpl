
<p class="message">
  <span class="emph">Note:</span> A reviewer's name in one of the following
    colors means:<br><br>
  <span class="diststatus-0">The paper is already assigned to the reviewer.
    Be careful with unselecting assigned papers. The reviewer may have started
    with reading the paper.
  </span><br>
  <span class="diststatus-2">The reviewer neither explicitly wants to review the
    paper nor prefers the topic of the paper.</span><br>
  <span class="diststatus-3">The reviewer prefers the topic of the paper.</span><br>
  <span class="diststatus-4">The reviewer explicitly wants to review the paper.
    </span><br><br>
  For each paper, you may deselect suggested reviewers except he/she is already
    assigned.<br>
  Finally, you can either confirm or dismiss the distribution.
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
  <input type="submit" name="confirm" value="Confirm distribution" class="button">
  <input type="submit" name="dismiss" value="Dismiss distribution" class="button">
</form>
