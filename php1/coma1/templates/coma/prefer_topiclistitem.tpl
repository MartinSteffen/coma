 <tr class="listitem-{line_no}"> 
    <td class="listitem-{line_no}">
      <a href="{basepath}user_paperdetails.php?paperid={topic_id}{&SID}">{topic}</a>
    </td> 
    <td class="listitem-{line_no}">&nbsp;</td>
    <td class="listitem-{line_no}" colspan="2">
      {if0<input type="radio" name="topic-{topic_id}" value="0" selected>
          <span class="attitude-none">no attitude</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="topic-{topic_id}" value="1">
          <span class="weak">prefer</span>}
      {if1<input type="radio" name="topic-{topic_id}" value="0">
          <span class="weak">no attitude</span> &nbsp;&nbsp;&nbsp;
          <input type="radio" name="topic-{topic_id}" value="1" selected>
          <span class="attitude-prefer">prefer</span>}
    </td>  
    <!--    
    <td class="listitem-{line_no}">
      {if0<span class="attitude-none">no attitude</span>}
      {if1<span class="attitude-prefer">prefer</span>}      
    </td>  
    <td class="listitem-{line_no}">
      <select name="topic-{topic_id}" size="1">
        <option>prefer</option>
        <option>no attitude</option>        
      </select>      
    </td>
  -->
  </tr>