<?
$sql = new SQL();
$sql->connect();

	$SQL =
	"SELECT DISTINCT role.conference_id, role.person_id, role.state,
	conference.id, conference.description, conference.name
	FROM role INNER JOIN conference ON (role.conference_id = conference.id)
	WHERE (person_id = '".$_SESSION['userID']."')
	AND (state = 1)";

	$list = $sql->query($SQL);

	$TPL['Conf'] = $list;

	template("FORUM_overview");
?>