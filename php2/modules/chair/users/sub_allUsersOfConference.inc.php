<?
if(isChair_Conference($_GET['confID']))
{
	$SQL = "select name from conference where id = ".$_GET['confID'];
    $result=mysql_query($SQL);
    $output = array();
	$persons = array();
	$output['confID'] = $_GET['confID'];
    if ($list = mysql_fetch_row ($result)) 	
    {
		$output['confName'] = $list[0];  
    }
	$SQL = "select distinct person.id, person.title, person.first_name, person.last_name, person.email 
			from person, role 
			where role.state = 1 
			and role.person_id = person.id
			and role.conference_id = ".$_GET['confID'];
						
    $result=mysql_query($SQL);
	$count = 0;	
    while ($list = mysql_fetch_row ($result)) 	
	{
		$person = array ();
		$person['personID'] = $list[0];
		$person['personName'] = $list[1]." ".$list[2]." ".$list[3];
		$person['email'] = $list[4];
		
		$SQL2 = "SELECT role_type from role 
		         WHERE state = 1 
				 AND person_id = ".$list[0]."
				 AND conference_id = ".$_GET['confID'];
		$result2=mysql_query($SQL2);
		$typeCount = 0;
		$person['roles'] = "";
		while ($list2 = mysql_fetch_row ($result2))
		{
			if ($typeCount > 0)
			{
				$person['roles'] .= ", ";				
			}
			$type = $list2[0];
			if ($type == 1)
			{
				$person['roles'] .= "Admin";
			}
			if ($type == 2)
			{
				$person['roles'] .= "Chair";
			}
			if ($type == 3)
			{
				$person['roles'] .= "Reviewer";
			}
			if ($type == 4)
			{
				$person['roles'] .= "Author";
			}									
			$typeCount++;
		}		 
 						
		$persons[$count] = $person;		
		$count = $count + 1;
	}

$output['persons'] = $persons;
$TPL['chair'] = $output;
template("CHAIR_allUsersOfConference");
$TPL['chair'] = "";
}
else redirect("logout",false,false,"error=1");	
?>