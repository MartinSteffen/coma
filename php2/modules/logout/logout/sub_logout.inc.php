<?
	if(isset($_GET['mode']))
	{
		if($_GET['mode'] == 1)
		{
			$TPL['login'] = "A problem occured with the session. Please login again.";
		}
	}
	//Destroy the session
	session_unset(); 
	session_destroy(); 
	
	//Go to the login site
	redirect();
?>