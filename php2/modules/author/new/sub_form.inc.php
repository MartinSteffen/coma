<?
$sql = new SQL();
$sql->connect();


//select all conferences with <user> is author
if (! isset ($_SESSION['userID'])) {
	redirect("logout", false, false, "error=1");
}

$SQL = "SELECT conference_id FROM role WHERE person_id = ".$_SESSION['userID']." AND role_type = 4";
$author = $sql->query($SQL);

// dump($author);
$conf = array();
foreach ($author as $row) {
	$SQL = "SELECT id, name, description FROM conference WHERE id = ".$row['conference_id'] . " AND paper_submission_deadline > CURRENT_DATE";
	$conf = array_merge($conf,$sql->query($SQL));
}

// var_dump($conf);
foreach($conf as $k => $v) {
	// var_dump($row);
	$conf[$k]['description'] = eregi_replace("\n", "<br>", $v['description']);
	// var_dump($row['description']);
}
$TPL['conf'] = $conf;
template("AUTHOR_new_conference");

?>
