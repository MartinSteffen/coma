<?
$sql = new SQL();
$sql->connect();

$messages = array();
$msg = array();

	function sub($msgid, $tiefe)
	{
		global $sql;
		$tiefe++;

			$SQL =
			"SELECT * FROM message
			WHERE (forum_id =".$_GET['forumID'].")
			AND (reply_to = ".$msgid.")
			ORDER BY send_time";

			$list = $sql->query($SQL);

			global $messages;

			foreach ($list as $entry)
			{
				$message = array();
				$message = array("ID"=>$entry[0],"sender"=>$entry[3],"subject"=>$entry[5], "text"=>$entry[6], "tiefe"=>$tiefe);
				$messages[] = $message;
				sub($entry[0], $tiefe);
			}

		$tiefe--;
	}

	function main()
	{
		$SQL =
		"SELECT * FROM message
		WHERE (forum_id =".$_GET['forumID'].")
		AND (reply_to is NULL)
		ORDER BY send_time";

		global $sql;

		$list = $sql->query($SQL);

		global $messages;

		foreach ($list as $entry)
		{
			$message = array();
			$message = array("ID"=>$entry[0],"sender"=>$entry[3],"subject"=>$entry[5], "text"=>$entry[6], "tiefe"=>0);
			$messages[] = $message;
			sub($entry[0], 0);
		}

		if (isset($_GET['msgid']))
		{
			$SQL =
			"SELECT * FROM message WHERE id=".$_GET['msgid'];

			$list = $sql->query($SQL);

			if (isset($list[0][2]))
			{
				$reply_to = $sql->query("SELECT subject, id FROM message WHERE id=".$list[0][2]);
			}

			if (isset($list[0][3]))
			{
				$sender = $sql->query("SELECT first_name, last_name, id FROM person WHERE id=".$list[0][3]);
			}

			global $msg;

			$msg = array("id"=>$list[0][0], "subject"=>$list[0][5], "text"=>$list[0][6], "sender"=>$sender[0][0].' '.$sender[0][1], "reply_to"=>$reply_to[0][0], "send_time"=>$list[0][4]);
		}
	}
	$list = $sql->query("SELECT id, forum_type, paper_id, conference_id, title FROM forum WHERE (id=".$_GET['forumID'].")");

	$go = false;

	if ($list[0][1] == 3)
	{
		if (isReviewer_Paper($list[0][2]))
		{
			$go = true;
//			echo "IS REVIEWER OF PAPER ".$list[0][2]."<br>";
		}
	} elseif ($list[0][1] == 2)	{
		if (isReviewer_Overall() OR isChair_Overall())
		{
//			echo "IS REVIEWER OR CHAIR<br>";
			$go = true;
		}
	} else {
		$go = true;
	}

	if ($go == true)
	{
		main();
		$TPL['Messageliste'] = $messages;
		$TPL['msg'] = $msg;
		$TPL['forum'] = array("confID"=>$list[0][3], "title"=>$list[0][4]);
		template("FORUM_viewforum");
	} else {
		redirect('forum',false, false, false);
	}
?>