<?
$sql = new SQL();
$sql->connect();

	if (isset($_GET['forumID']))
	{
		$TPL['forumID'] = $_GET['forumID'];
	}

	if (isset($_GET['msgid']))
	{
		$TPL['msgid'] = $_GET['msgid'];
		$subject = $sql->query("SELECT id, subject FROM message WHERE (id='".$_GET['msgid']."')");
		$TPL['subject'] = ''.$subject[0][1];
	}

	$list = $sql->query("SELECT id, forum_type, paper_id FROM forum WHERE (id=".$_GET['forumID'].")");

	$go = false;

	if ($list[0][1] == 3)
	{
		if (isReviewer_Paper($list[0][2]))
		{
			$go = true;
		}
	} elseif ($list[0][1] == 2)	{
		if (isReviewer_Overall() OR isChair_Overall())
		{
			$go = true;
		}
	} else {
		$go = true;
	}

	if ($go == true)
	{
		template("FORUM_newmessage");
	} else {
		redirect('forum',false, false, false);
	}
?>