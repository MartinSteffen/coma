<?
$sql = new SQL();
$sql->connect();


//select all conferences with <user> is author
	$SQL = "SELECT conference_id FROM role WHERE person_id = ".$_SESSION['userID']." AND role_type = 4";
	$author = $sql->query($SQL);
//select all conferences where <user> uploaded a paper 
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
//get all allowed conferences, where <user> has no paper
	$c = array_diff($a, $b);
	if (count($c) > 0)
//if there is any conference
	{
		$selectConference = true;
		$Conferences = $c;
		$conf = array();
		if(count($Conferences) == 1){
//if only one conference is available
			foreach($Conferences as $value)		//wird nur einmal ausgeführt, ist aber notwendig, da der erste Index im Array nicht bekannt ist
				{
//jump directly to inputform
				redirect("author", "new", "create", "cid=".$value);
				}
		}
		foreach ($Conferences as $conference_id)
//else select conference in template <AUTHOR_choose_conference>
		{
			$SQL = "SELECT id, name, description FROM conference WHERE id = ".$conference_id;
			$conf = array_merge($conf,$sql->query($SQL));
		}			
		$TPL['conf'] = $conf;
		template("AUTHOR_choose_conference");
		exit();
	}
	else
//if no conference is available, "say sorry"
	{
		$selectConference = false;
		template("AUTHOR_no_new_papers");
		exit();
	}
?>
