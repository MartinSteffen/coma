<br>
<p>{replystring}<br>
<form action="{basepath}forum.php{?SID}" method="post">
  <input type="hidden" name="reply-to" value="{message-id}">
  <input type="hidden" name="forumid" value="{forum-id}">
  Subject: <input type="text" size="40" name="subject" value="{subject}"><br>
  Message: <textarea cols="40" rows="10" wrap="soft" name="text">{text}</textarea><br>
  {editform}
  <input type="submit" value="send">
</p>
