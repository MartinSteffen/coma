<?
$sql = new SQL();
$sql->connect();
if(isReviewer_Overall())
{
	// Erst alle Einträge in Preferstopic löschen

	$SQL = "DELETE FROM preferstopic WHERE person_id = ".$_SESSION['userID'];
	$result = mysql_query($SQL);

	// Alle Topics aller Konferenzen finden

	$SQL =
	 "SELECT role.conference_id, role.person_id, role.role_type,
	   topic.id, topic.conference_id, topic.name,
	   conference.name, conference.id, role.state
	  FROM role INNER JOIN topic ON (role.conference_id = topic.conference_id)
	  INNER JOIN conference ON (role.conference_id = conference.id)
	  WHERE (role.person_id = ".$_SESSION['userID'].") AND (role.state = 1) ORDER BY topic.conference_id, topic.name";

	$result = mysql_query($SQL);

	while ($list = mysql_fetch_row($result))
	{
		$topic = array ();
		$topic = array ("conference"=>$list[6],"conferenceid"=>$list[7], "topicname"=>$list[5],"topicid"=>$list[3]);

		// Gesetzte Topics ermitteln und in der DB speichern
		if (isset($_POST['C'.$list[7].'T'.$list[3]]))
		{
			$SQL = "INSERT INTO preferstopic VALUES(".$_SESSION['userID'].", ".$list[3].")";
			$result2 = mysql_query($SQL);
			// echo "SET PREFEREDTOPIC IN Conference: ".$list[6]." Topic: ".$list[5]." Wert: ".$_POST['C'.$list[7].'T'.$list[3]]."\r\n";
		}
	}

	redirect("reviewer","topic",false,false);
} else redirect("logout",false,false,"error=1");
?>