<?
$sql = new SQL();
$sql->connect();

$reply = 'NULL';
if ($_POST['msgid']!="")
{
	$reply = $_POST['msgid'];
}

$result = $sql->insert("INSERT INTO message VALUES(0, ".$_POST['forumID'].", ".$reply.", ".$_POST['sender'].",
						 CURRENT_TIMESTAMP, '".htmlentities($_POST['subject'])."', '".nl2br(htmlentities($_POST['text']))."')");

redirect('forum','viewforum',false,'forumID='.$_POST['forumID']);

?>