<?
$sql = new SQL();
$sql->connect();
if(isReviewer_Overall())
{
   $SQL = "SELECT
   			deniespaper.person_id, deniespaper.paper_id,
			paper.id, paper.state, paper.conference_id, paper.title,
			conference.id, conference.name
			FROM deniespaper
			  INNER JOIN paper ON (deniespaper.paper_id = paper.id)
			  INNER JOIN conference ON (paper.conference_id = conference.id)
			WHERE
			  (deniespaper.person_id = ".$_SESSION['userID'].")";

//	echo $SQL;

	$result = mysql_query($SQL);
	$count = 0;
	$paperlist = array();

	while ($list = mysql_fetch_row ($result))
	{
		$paper = array();
		$paper = array("text"=>$list[7]." : ".$list[5], "id"=>$list[2]);

		$paperlist[$count] = $paper;
		$count++;
	}

	$TPL['paperlist'] = $paperlist;

	template("REVIEWER_denied");
} else redirect("logout",false,false,"error=1");
?>