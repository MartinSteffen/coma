<?
if(isset($_SESSION['userID']))
{
	if (isset($_POST['Accept']))
	{
		if ($_POST['state'] == 3)
		{
			$SQL = "UPDATE role 
   				    SET state = 1
					WHERE person_id = ".$_SESSION['userID']."
					AND conference_id = ".$_POST['confID']."
					AND role_type = ".$_POST['roleType'];
			$result=mysql_query($SQL);			
		}
		else redirect("logout",false,false,"error=1");		
	}
	if (isset($_POST['Deny']))
	{
		if (($_POST['state'] == 1) || ($_POST['state'] == 3))
		{
			$SQL = "UPDATE role 
   				    SET state = 2
					WHERE person_id = ".$_SESSION['userID']."
					AND conference_id = ".$_POST['confID']."
					AND role_type = ".$_POST['roleType'];
			$result=mysql_query($SQL);			
		}
		else redirect("logout",false,false,"error=1");	
	}	


	redirect("profile","roles");
}
else redirect("logout",false,false,"error=1");	
?>