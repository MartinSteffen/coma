<table class="viewtable">
  <tr class="viewheader">
    <th class="viewheader" colspan="2">{name}</th>
  </tr>
  <tr class="viewline">
    <td class="viewline" colspan="2">{description}</td>
  </tr>
  <tr class="viewline">    
    <td class="viewline">Takes place at:</td>
    <td class="viewline">{date}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">Website:</td>
    <td class="viewline">{if1<a href="{link}" target="_blank" class="link">}{link}{if1</a>}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline" colspan="2">&nbsp;</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">Number of papers to chose:</td>
    <td class="viewline">{paper_number}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">Submit paper abstracts until:</td>
    <td class="viewline">{abstract_deadline}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">Submit papers until:</td>
    <td class="viewline">{paper_deadline}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">Submit final paper version until:</td>
    <td class="viewline">{final_deadline}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">Submit reviews until:</td>
    <td class="viewline">{review_deadline}</td>
  </tr>
  <tr class="viewline">
    <td class="viewline">Decide papers until:</td>
    <td class="viewline">{notification}</td>
  </tr>  
</table>

<p>&nbsp;</p>

<p class="message2">
  Show <a href="{basepath}user_users.php{?SID}" class="link">list of all members</a> of this conference.<br>
  Show <a href="{basepath}user_papers.php{?SID}" class="link">list of all papers</a> submitted in for this conference.
</p>
