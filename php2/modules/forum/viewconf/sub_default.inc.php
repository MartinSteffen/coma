<?
$sql = new SQL();
$sql->connect();

	// Alle Paper in das Forum einfügen

	$list = $sql->query("SELECT id, conference_id, title FROM paper WHERE conference_id = ".$_GET['confID']);

	foreach ($list as $paper)
	{
		$res = $sql->query("SELECT COUNT(*) FROM forum WHERE (conference_id = ".$_GET['confID'].") AND (paper_id = ".$paper[0].")");
		if ($res[0][0] == 0)
		{
			$SQL = "INSERT INTO forum VALUES(0, ".$_GET['confID'].", '".$paper[2]."', 3, ".$paper[0].")";
			$res = $sql->insert($SQL);
		}
	}

	// ----

	$SQL =
	"SELECT id, conference_id, title, forum_type, paper_id
	FROM forum
	WHERE (conference_id = ".$_GET['confID'].")";

	if (!isChair_Overall() AND !isReviewer_Overall())
	{
		$SQL .= "AND (forum_type!=2) ";
	}

	if (!isReviewer_Overall())
	{
		$SQL .= "AND (forum_type!=3) ";
	} else {
		// ist Reviewer, nur die Paper anzeigen, für die der Reviewer eingetragen ist
		$SQL =
		  "SELECT
		  forum.id, forum.conference_id, forum.title, forum.forum_type, forum.paper_id,
		  reviewreport.reviewer_id, reviewreport.paper_id
		  FROM forum INNER JOIN reviewreport ON (forum.paper_id = reviewreport.paper_id)
		  WHERE (reviewreport.reviewer_id = ".$_SESSION['userID'].")
		  AND (forum.conference_id = ".$_GET['confID'].")";

//		  echo $SQL;

		  $paper = $sql->query($SQL);

		  $SQL =
			"SELECT id, conference_id, title, forum_type, paper_id
			FROM forum
			WHERE (conference_id = ".$_GET['confID'].") AND (forum_type != 3)";

	}

	$list = $sql->query($SQL);
	$list = array_merge($list, $paper);

	$TPL['Forumliste'] = $list;

	template("FORUM_viewconf");
?>