<?
$sql = new SQL();
$sql->connect();

$reply = 'NULL';
if ($_POST['msgid']!="")
{
	$reply = $_POST['msgid'];
}

$SQL="INSERT INTO message VALUES(0, ".$_POST['forumID'].", ".$reply.", ".$_POST['sender'].",
						 CURRENT_TIMESTAMP, '".htmlentities($_POST['subject'])."', '".nl2br(htmlentities($_POST['text']))."')";

echo "Debug: ".$SQL;

$result = $sql->insert($SQL);

redirect('forum','viewforum',false,'forumID='.$_POST['forumID']);

?>