<?
if(isChair_Person($_GET['personID']))
{
	$SQL = "SELECT title, first_name, last_name, affiliation, email, phone_number,
			fax_number, street, postal_code, city, state, country
			FROM person
			WHERE id = ".$_GET['personID'];
    $result=mysql_query($SQL);
    $person = array();
    while ($list = mysql_fetch_row ($result)) 	
    {
       $person['personID'] = $_GET['personID'];
	   $person['title'] = $list[0];
	   $person['first_name'] = $list[1];
	   $person['last_name'] = $list[2];
	   $person['affiliation'] = $list[3];
	   $person['email'] = $list[4];
	   $person['phone_number'] = $list[5];
	   $person['fax_number'] = $list[6];
	   $person['street'] = $list[7];
	   $person['postal_code'] = $list[8];
	   $person['city'] = $list[9];
	   $person['state'] = $list[10];
	   $person['country'] = $list[11];	   
    }
	
	
	//Get the roles
	$SQL = "SELECT Y.role_type, Y.state, conference.id, conference.name 
		    FROM role X, role Y, conference
			WHERE X.role_type = 2
			AND X.state = 1
			AND X.person_id = ".$_SESSION['userID']."
			AND X.conference_id = Y.conference_id
			AND Y.conference_id = conference.id
			AND Y.person_id = ".$_GET['personID']."
			AND NOT (Y.role_type = 0)
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
	
		
	$output = array();
	$output['person'] = $person;				
	$output['roles'] = $roles;

$TPL['chair'] = $output;
template("CHAIR_user");
}
else redirect("logout",false,false,"error=1");	
?>