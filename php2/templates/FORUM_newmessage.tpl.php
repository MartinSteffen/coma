<?
	include("header.tpl.php");
?>
<form method="POST" action="index.php?m=forum&a=message&s=new">
<input type="hidden" name="sender" value="<? echo $_SESSION['userID']; ?>">
<input type="hidden" name="forumID" value="<? echo $TPL['forumID']; ?>">
<input type="hidden" name="msgid" value="<? echo $TPL['msgid']; ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%">Subject:
    <td width="100%"><input type="text" name="subject" size="66">
  </tr>
  <tr>
    <td width="20%">Text:
    <td width="100%"><textarea rows="10" name="text" cols="50"></textarea>
  </tr>
</table>
<p align="left"><input type="submit" value="Senden"></p>
</form>
<?
	include("footer.tpl.php");
?>