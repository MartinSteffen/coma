  <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <!--<a href="{basepath}user_paperdetails.php?paperid={paper_id}{&SID}" class="link">{title}</a>-->
      <a href="{basepath}chair_paperreviews.php?paperid={paper_id}{&SID}" class="link">{title}</a>
    </td>
    <td class="listitem-{line_no}">
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}" class="link">{author_name}</a>
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="status-unreviewed">unreviewed</span>
      <form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="changestatus">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="submit" value="accept" class="smallbutton">
        <input type="submit" name="submit" value="reject" class="smallbutton">
      </form>
      }
      {if1<span class="status-reviewed">reviewed</span>
      <form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="changestatus">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="submit" value="accept" class="smallbutton">
        <input type="submit" name="submit" value="reject" class="smallbutton">
      </form>
      }
      {if2<span class="status-critical">conflicting</span>
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
      {if4<span class="status-rejected">rejected</span>
      <form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="resetstatus">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="submit" value="reset status" class="smallbutton">
      </form>
      }
    </td>
    <td class="listitem-{line_no}">{num_reviews}</td>
    <td class="listitem-{line_no}">{avg_rating}</td>
    <td class="listitem-{line_no}">
      {if6<span class="status-critical">{variance}</span>}
      {if7{variance}}
    </td>
    <td class="listitem-{line_no}">
      {if5<a href="{basepath}get_paper.php?paperid={paper_id}{&SID}" class="buttonlink">view&nbsp;paper</a>}
    </td>
    <td class="listitem-{line_no}">
      {reviewers}
    </td>
    <td class="listitem-{line_no}">
      <a href="{basepath}chair_reviewsreviewer.php?paperid={paper_id}{&SID}" class="buttonlink">Add/Del reviewers</a>
    </td>
  </tr>