  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&popup}{&SID}" class="link" target="_blank">{title}</a>      
    </td>
    <td class="listitem-{line_no}">
      <a href="{basepath}user_userdetails.php?userid={author_id}{&popup}{&SID}" class="link" target="_blank">{author_name}</a>
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="status-unreviewed">unreviewed</span><br>
      <form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="changestatus">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="submit" value="accept" class="smallbutton">
        <input type="submit" name="submit" value="reject" class="smallbutton">
      </form>
      }
      {if1<span class="status-reviewed">reviewed</span><br>
      <form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="changestatus">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="submit" value="accept" class="smallbutton">
        <input type="submit" name="submit" value="reject" class="smallbutton">
      </form>
      }
      {if2<span class="status-critical">critical</span><br>
      <form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="changestatus">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="submit" value="accept" class="smallbutton">
        <input type="submit" name="submit" value="reject" class="smallbutton">
      </form>
      }
      {if3<span class="status-accepted">accepted</span>
      <form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="resetstatus">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="submit" value="reset status" class="smallbutton">
      </form>
      }
      {if4<span class="status-rejected">rejected</span><br>
      <form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="resetstatus">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="submit" value="reset status" class="smallbutton">
      </form>
      }
    </td>
    <td class="listitem-{line_no}" nowrap>{num_reviews}</td>
    <td class="listitem-{line_no}">{avg_rating}</td>
    <td class="listitem-{line_no}">
      {if6<span class="status-critical">{variance}</span>}
      {if7{variance}}
    </td>
    <td class="listitem-{line_no}" nowrap>
      {reviewers}
    </td>
  </tr>
  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <a href="{basepath}chair_paperreviews.php?paperid={paper_id}{&SID}" class="buttonlink">View review report</a> 
    </td>
    <td class="listitem-{line_no}">
      {if5<a href="{basepath}get_paper.php?paperid={paper_id}{&SID}" class="buttonlink">View&nbsp;paper</a>}&nbsp;
    </td>
    <td class="listitem-{line_no}" colspan="2">
      {if8<a href="{basepath}chair_reviews.php?createforum&amp;paperid={paper_id}{&SID}"
             class="buttonlink">Start discussion</a> <br>}&nbsp;
    </td>
    <td class="listitem-{line_no}" colspan="2">
      {if9<a href="{basepath}forum.php?forumsel={forum_id}{&SID}"
             class="buttonlink">Enter discussion</a> <br>}    
    </td>    
    <td class="listitem-{line_no}">
      <a href="{basepath}chair_reviewsreviewer.php?paperid={paper_id}{&SID}" class="buttonlink">Edit reviewers</a>
    </td>
  </tr>