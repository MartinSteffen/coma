<a href="{basepath}user_userdetails.php?userid={rev_id}{&SID}" class="link">
  <span class="diststatus-{status}">{rev_name}</span>
<form action="{basepath}chair_distribution.php{?SID}" method="post" accept-charset="UTF-8">
  <input type="checkbox" name=" " value="1">
  
  <input type="hidden" name="action" value="dismiss">
  <input type="hidden" name="paperid" value="{paper_id}">
  <input type="hidden" name="reviewerid" value="{rev_id}">
  <input type="hidden" name="reviewerarrayindex" value="{rev_array_index}">
  <input type="submit" name="submit" value="dismiss" class="smallbutton">

<!--</a><button name="dismissReviewer" type="button" class="smallbutton" value="dismiss"
  onClick="self.location.href='{basepath}chair_distribution.php?paperid={paper_id}&reviewerid={rev_id}{&SID}'">
  dismiss</button>-->

&nbsp;
