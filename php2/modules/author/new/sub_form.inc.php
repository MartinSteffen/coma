<?
$sql = new SQL();
$sql->connect();

 // $SQL = "SELECT role.conference_id FROM role, paper WHERE role.person_id = ".$_SESSION['userID']." AND role.role_type = 4 AND paper.author_id != ".$_SESSION['userID'];//." AND NOT paper.conference_id = 2";

	$SQL = "SELECT conference_id FROM role WHERE person_id = ".$_SESSION['userID']." AND role_type = 4";
	$author = $sql->query($SQL);
	$SQL = "SELECT conference_id FROM paper WHERE author_id = ".$_SESSION['userID'];
	$paper = $sql->query($SQL);
	$a = $author;
/*	while ($row = mysql_fetch_row($author))
	{
		$a[] = $row[0];
	}
*/	
	$b = $paper;
/*  while ($row = mysql_fetch_row($paper))
	{
		$b[] = $row[0];
	}
*/	
	$c = array();
	$c = array_diff($a, $b);
	if (count($c) > 0)
	{
		$selectConference = true;
		$Conferences = $c;
//		var_dump($Conferences);
		$conf = array();
		for ($i = 1; $i < (count($Conferences))+1; $i++)
		{
			$SQL = "SELECT name, description FROM conference WHERE id = ".$Conferences[$i];
			$confinf = $sql->query($SQL);
//			var_dump($confinf);
			
//			echo($Conferences[$i]);
//			exit();
			$conf[] = mysql_fetch_row($confinf);
		}
		if(count($Conferences) == 1){
 			redirect("author", "new", "create", "cid=".$Conferences[0]);
		}
	}
	else
	{
		$selectConference = false;
	}
	include('templates/AUTHOR_new_form.tpl.php');
?>
