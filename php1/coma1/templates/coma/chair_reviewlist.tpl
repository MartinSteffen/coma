
<p class="message">
  It is <span class="emph">recommended</span> to proceed as follows:<br>
  Let CoMa make a <a href="{basepath}chair_distribution.php{?SID}" class="link">
  suggestion</a> (may take several seconds) of a possible paper/reviewer
  distribution for papers that are not assigned to the default number of
  reviewers yet.<br>
  If the 'Auto add reviewers' flag is activated, CoMa will try to suggest n+m
  reviewers for ambiguous papers if possible, where n ist the default number of
  reviewers per paper and m is the number of additional reviewers.
  (See <a href="{basepath}chair_confconfig.php?advancedconfig{&SID}" class="link">Conference
  Config</a> for more information.)<br>
  You may edit the suggested distribution and afterwards confirm it.<br>
  Later, you can assign several papers by hand (this formular) or restart the
  suggestion process.  
</p>

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
    <th class="listheader">&nbsp;</th>
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
    <th class="listheader">Assigned reviewers</th>
   </tr>
   {lines}
</table>
