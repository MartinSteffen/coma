<?
$sql = new SQL();
$sql->connect();
if(isReviewer_Overall())
{
	$SQL =
	 "SELECT role.conference_id, role.person_id, role.role_type,
	   topic.id, topic.conference_id, topic.name,
	   conference.name, conference.id
	  FROM role INNER JOIN topic ON (role.conference_id = topic.conference_id)
	  INNER JOIN conference ON (role.conference_id = conference.id)
	  WHERE (role.person_id = ".$_SESSION['userID'].") ORDER BY topic.conference_id, topic.name";

	$result = mysql_query($SQL);
	$topiclist = array();

	while ($list = mysql_fetch_row($result))
	{
		$wert = "";
		$SQL = "SELECT * FROM preferstopic WHERE person_id = (".$_SESSION['userID'].") AND (topic_id = ".$list[3].")";

		$result2 = mysql_query($SQL);
		if ($result2)
		{
			if ($l2 = mysql_fetch_row($result2))
			{
			  $wert = "checked";
			}
		}

		$topic = array ();
		$topic = array ("conference"=>$list[6],"conferenceid"=>$list[7], "topicname"=>$list[5],"topicid"=>$list[3], "value"=>$wert);
		$topiclist[] = $topic;
	}

	$TPL['topiclist'] = $topiclist;

	template("REVIEWER_choosetopic");
} else redirect("logout",false,false,"error=1");
?>