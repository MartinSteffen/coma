<?
if(isset($_SESSION['userID']))
{
	if(isset($_POST['Submit']))
	{
		$SQL = "DELETE FROM role 
				WHERE role_type = 5
				AND person_id = ".$_SESSION['userID'];
		$result=mysql_query($SQL);
		
		if (isset($_POST['conferences']))
		{		
			foreach ($_POST['conferences'] as $confID)
			{					
				$SQL = "INSERT INTO role (person_id, conference_id, role_type, state)
						 VALUES (".$_SESSION['userID'].",".$confID.",5,1)";
				$result=mysql_query($SQL);
			}
		}
	}
	
	$SQL = "SELECT id, name from conference";
	$result=mysql_query($SQL);
	$conferences = array();
	$count = 0;
	while ($list = mysql_fetch_row ($result))
	{
		$conference = array();
		$conference['confID'] = $list[0];
		$conference['confName'] = $list[1];
		
		$SQL2 = "SELECT person_id from role 
				 WHERE role_type = 5
				 AND state = 1
				 AND conference_id = ".$list[0]."
				 AND person_id = ".$_SESSION['userID'];
		$result2=mysql_query($SQL2);
		if ($list2 = mysql_fetch_row ($result2))
		{
			$conference['check'] = true;
		}
		else
		{
			$conference['check'] = false;
		}
		$conferences[$count] = $conference;
		$count++;
	}

	$TPL['profile'] = $conferences;
	template("PROFILE_participate");	
}
else redirect("logout",false,false,"error=1");	
?>			