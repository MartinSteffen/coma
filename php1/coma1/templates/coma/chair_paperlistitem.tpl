 <tr class="listitem-{line_no}">
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={paper_id}{&SID}" class="link">{title}</a>
    </td> 
    <td class="listitem-{line_no}"> 
      <a href="{basepath}user_userdetails.php?userid={author_id}{&SID}" class="link">{author_name}</a>
    </td>
    <td class="listitem-{line_no}">
      {if0<span class="status-unreviewed">unreviewed</span>}
      {if1<span class="status-reviewed">reviewed</span>}
      {if2<span class="status-critical">conflicting</span>}
      {if3<span class="status-accepted">accepted</span>}
      {if4<span class="status-rejected">rejected</span>}
      {if5<form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
            <input type="hidden" name="action" value="changestatus">
            <input type="hidden" name="paperid" value="{paper_id}">
            <input type="submit" name="submit" value="accept" class="smallbutton">
            <input type="submit" name="submit" value="reject" class="smallbutton">
          </form>}
      {if6<form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
            <input type="hidden" name="action" value="resetstatus">
            <input type="hidden" name="paperid" value="{paper_id}">            
            <input type="submit" name="submit" value="reset status" class="smallbutton">
          </form>}
    </td>
    <td class="listitem-{line_no}">{avg_rating}</td>
    <td class="listitem-{line_no}">{variance}</td>
    <td class="listitem-{line_no}">{last_edited}</td>
    <td class="listitem-{line_no}">
      {if5<a href="{basepath}get_paper.php?paperid={paper_id}{&SID}" class="link">view paper</a>}
    </td>
    <td class="listitem-{line_no}">                  
      <form action="{basepath}chair_paperreviews.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="submit" name="viewreviews" value="view review report" class="smallbutton">
      </form>      
    </td>
    <td class="listitem-{line_no}">
      <form action="{basepath}chair_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="paperid" value="{paper_id}">
        <input type="checkbox" name="confirm_delete" value="1">
        <input type="submit" name="submit" value="delete" class="smallbutton">
      </form>
    </td>
  </tr>
