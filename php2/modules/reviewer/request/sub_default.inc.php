<?
$sql = new SQL();
$sql->connect();
if(isReviewer_Overall())
{
	$SQL =
	 "SELECT DISTINCT role.conference_id,
	   paper.conference_id, paper.id, paper.state, paper.title,
	   role.person_id,
	   conference.review_deadline, conference.id, conference.name
	  FROM role INNER JOIN paper ON (role.conference_id = paper.conference_id)
	  INNER JOIN conference ON (paper.conference_id = conference.id)
	  WHERE (role.person_id = ".$_SESSION['userID'].")
	  AND (paper.state = 0)
	  AND (conference.review_deadline >= CURRENT_DATE)";

	$result = mysql_query($SQL);
	$paperlist = array();

	while ($list = mysql_fetch_row($result))
	{
		$paper = array ();
		$paper = array ("conference"=>$list[8],"paper"=>$list[4],"paperid"=>$list[2]);
		$paperlist[] = $paper;
	}

	$TPL['paperlist'] = $paperlist;

	template("REVIEWER_choosepaper");
}
?>