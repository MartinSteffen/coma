
{if9<p class="message">{message}</p>}

<table class="list">
  <tr class="listheader">
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=1{&SID}" class="order{if1-active}">Title</a>
    </th> 
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=2{&SID}" class="order{if2-active}">Author</a>
    </th> 
    <th class="listheader">Status</th>    
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=4{&SID}" class="order{if4-active}">Rating</a>
    </th>
    <th class="listheader">Variance</th>
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=5{&SID}" class="order{if5-active}">Last edit</a>
    </th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">&nbsp;</th>
  </tr>
  {lines}
</table>

<p>&nbsp;</p>

<p class="message2">
  Click on any paper to view the details for the paper.<br>
  Click on any author to view the details for the user.<br>
  You can change the status of the paper by using the buttons <span class="emph">Accept</span>,
  <span class="emph">Reject</span>, and <span class="emph">Reset</span> the status.
</p>
