<?
$sql = new SQL();
$sql->connect();

   $SQL = "SELECT
   			reviewreport.reviewer_id, reviewreport.paper_id,
			paper.id, paper.state, paper.conference_id, paper.title,
			conference.id, conference.review_deadline, conference.name
			FROM reviewreport
			  INNER JOIN paper ON (reviewreport.paper_id = paper.id)
			  INNER JOIN conference ON (paper.conference_id = conference.id)
			WHERE
			  (conference.review_deadline >= CURRENT_DATE)
			  AND (paper.state = 0)
			  AND (reviewreport.reviewer_id = ".$_SESSION['userID']." )";

	$result = mysql_query($SQL);
	$count = 0;
	$paperlist = array();

	while ($list = mysql_fetch_row ($result))
	{
		$paper = array();
		$paper = array("text"=>$list[5], "id"=>$list[2]);

		$paperlist[$count] = $paper;
		$count++;
	}

	$TPL['paperlist'] = $paperlist;

template("REVIEWER_review");
?>