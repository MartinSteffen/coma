<?
$sql = new SQL();
$sql->connect();
if(isChair_Person($_POST['userID']))
{
	if (isset($_POST['conferences']))
	{
		foreach ($_POST['conferences'] as $conference)
		{
			if(isChair_Conference($conference))
			{
				if (isset($_POST['roles']))
				{
					foreach ($_POST['roles'] as $roleType)
					{
						if(!($roleType == 1))
						{
							$SQL = "SELECT state FROM role
									WHERE person_id = ".$_POST['userID']."
									AND conference_id = ".$conference."
									AND role_type = ".$roleType;
							$result=mysql_query($SQL);
							if ($list = mysql_fetch_row ($result)) 
							{	
								if ($list[0] == 2)
								{
									$SQL2 = "UPDATE role
											 SET state = 3
											 WHERE person_id = ".$_POST['userID']."
											 AND conference_id = ".$conference."
											 AND role_type = ".$roleType;
									$result2=mysql_query($SQL2);		 
								}
							}
							else
							{
								$SQL2 = "INSERT INTO role (person_id, conference_id, role_type, state)
						 				VALUES (".$_POST['userID'].",".$conference.",".$roleType.",3)";
								$result2=mysql_query($SQL2);
							}
						}
						else redirect("logout",false,false,"error=1");
					}
				}
			}
			else redirect("logout",false,false,"error=1");	
		}		
	}
	redirect("chair","users","user","personID=".$_POST['userID']);
}
else redirect("logout",false,false,"error=1");	
?>