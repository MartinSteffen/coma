
{if9<p class="message-failed">{message}</p>}
 
{ifREVIEWERNOTIFY
<p class="message">
  <span class="emph">The review phase has started.</span>
  So if you have made your decisions about the review distribution, click here to
  <a href="{basepath}notify_reviewers.php{&SID}" class="mail">notify all reviewers by mail</a>
  about the papers distributed to them.
</p>}

{ifAUTHORNOTIFY
<p class="message">
  <span class="emph">The review phase is over.</span>
  So, accept or deny papers for the conference accordingly to their rating.<br>
  If you have made your decisions which papers to accept, click here to
  <a href="{basepath}notify_accepted.php{&SID}" class="mail">notify all reviewers and authors by mail</a>
  about the accepted papers.
</p>}

<table class="list">
  <tr class="listheader">
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=1{&SID}" class="order{if1-active}">Title</a>
    </th>
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=2{&SID}" class="order{if2-active}">Author</a>
    </th>
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=3{&SID}" class="order{if3-active}">Status</a>
    </th>
    <th class="listheader">Reviews</th>
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=4{&SID}" class="order{if4-active}">Rating</a>
    </th>    
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=6{&SID}" class="order{if6-active}">Ambiguity</a>
    </th>
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=5{&SID}" class="order{if5-active}">Last edit</a>
    </th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">Del. mark</th>
  </tr>
  {lines}
</table>

<p>&nbsp;</p>

<p class="message">
  Click on any paper to view the details for the paper.<br>
  Click on any author to view the details for the user.<br>
  To delete a paper you have to mark the checkbox next to the <span class="emph">delete</span> button
  for confirmation.<br>
  You can change the status of the paper by using the buttons <span class="emph">Accept</span>,
  <span class="emph">Reject</span>, and <span class="emph">Reset</span> the status.
</p>
