
<a href="{basepath}user_userdetails.php?userid={rev_id}{&SID}" class="link">
  <span class="diststatus-{status}">{rev_name}</span>
</a>
<button name="dismissReviewer" type="button" class="smallbutton" value="dismiss"
  onClick="self.location.href='{basepath}chair_distribution.php?paperid={paper_id}&reviewerid={rev_id}{&SID}'">
  dismiss</button>
