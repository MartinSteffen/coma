<?
$sql = new SQL();
$sql->connect();
if(isset($_SESSION['userID']))
{
	$SQL = "SELECT role.role_type, role.state, conference.id, conference.name 
		    FROM role, conference
			WHERE role.person_id = ".$_SESSION['userID']."
			AND role.conference_id = conference.id
			AND NOT (role.role_type = 0)
			ORDER BY conference.id";
    $result=mysql_query($SQL);
    $roles = array();
	$count = 0;
    while ($list = mysql_fetch_row ($result)) 	
    {
		$selRole = array();
		$selRole['roleType'] = $list[0];
		$selRole['state'] = $list[1];
		$selRole['confID'] = $list[2];
		$selRole['confName'] = $list[3];
		if ($list[0] == 1)
		{
			$selRole['roleTypeText'] = "Admin";
		}
		if ($list[0] == 2)
		{
			$selRole['roleTypeText'] = "Chair";
		}
		if ($list[0] == 3)
		{
			$selRole['roleTypeText'] = "Reviewer";
		}
		if ($list[0] == 4)
		{
			$selRole['roleTypeText'] = "Author";
		}
		if ($list[0] == 5)
		{
			$selRole['roleTypeText'] = "Participant";
		}		
		
		if ($list[1] == 1)
		{
			$selRole['stateText'] = "Active";
			$selRole['stateColor'] = "textBlue";			
		}	
		if ($list[1] == 2)
		{
			$selRole['stateText'] = "Denied invitation";
			$selRole['stateColor'] = "textRed";				
		}	
		if ($list[1] == 3)
		{
			$selRole['stateText'] = "Invited";
			$selRole['stateColor'] = "textGreen";				
		}					

		$roles[$count] = $selRole;
		$count++;
	}

$TPL['profile'] = $roles;
template("PROFILE_roles");

}
else redirect("logout",false,false,"error=1");	
?>	