<?
	$link = mysql_connect ("localhost","testUser","testPass");
	$base = mysql_select_db ("testComma");
	$SQL = "select role.person_id from paper, role 
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = paper.conference_id 
			and paper.id = ".$_POST['paperID']; 

    $result=mysql_query($SQL);	
    if ($dummy = mysql_fetch_row ($result)) 	
	{
		$SQL = "update paper set state = ".$_POST['stateID']." where id = ".$_POST['paperID'];
		$result=mysql_query($SQL);	
		redirect("chair","papers","paper","paperID=".$_POST['paperID']);
	}	
?>	