<?
$sql = new SQL();
$sql->connect();

 // $SQL = "SELECT role.conference_id FROM role, paper WHERE role.person_id = ".$_SESSION['userID']." AND role.role_type = 4 AND paper.author_id != ".$_SESSION['userID'];//." AND NOT paper.conference_id = 2";

	$SQL = "SELECT conference_id FROM role WHERE person_id = ".$_SESSION['userID']." AND role_type = 4";
	$author = $sql->query($SQL);
	$SQL = "SELECT conference_id FROM paper WHERE author_id = ".$_SESSION['userID'];
	$paper = $sql->query($SQL);
	$a = array();

	foreach($author as $row) {
		$a[] = $row['conference_id'];
	}
	
	$b = array();
	foreach($paper as $row) {
		$b[] = $row['conference_id'];
	}
	$c = array_diff($a, $b);
	if (count($c) > 0)
	{
		$selectConference = true;
		$Conferences = $c;
//		var_dump($Conferences);
		$conf = array();
		if(count($Conferences) == 1){
 			redirect("author", "new", "create", "cid=".$Conferences[0]);
		}
		foreach ($Conferences as $conference_id)
		{
			$SQL = "SELECT name, description FROM conference WHERE id = ".$conference_id;
			$conf = array_merge($conf,$sql->query($SQL));
		}
	}
	else
	{
		$selectConference = false;
	}
	include('templates/AUTHOR_new_form.tpl.php');
?>
