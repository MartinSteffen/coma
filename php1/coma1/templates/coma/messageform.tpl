<br>
<table class="list" width="100%">
  <tr class="listitem-2">
    <td align="center"> <b> <u> {replystring} </u> </b><br> </td>
  </tr>
  <tr class="listitem-2">
     <td>

       <form action="{basepath}forum.php{?SID}#m{message-id}" method="post" accept-charset="UTF-8">
       <input type="hidden" name="reply-to" value="{message-id}">
       <input type="hidden" name="forumid" value="{forum-id}">
       &nbsp; <b> Subject: </b>  <br><input type="text" size="80" name="subject" value="{subject}"><br>
       &nbsp; <b> Message: </b>  <br><textarea cols="80" rows="5" name="text">{text}</textarea><br>
       {editform}
       <input type="submit" name="reply" class="button" value="send"></form>
    </td>
  </tr>
</table>
