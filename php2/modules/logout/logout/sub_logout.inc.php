<?
	//Destroy the session
	session_unset(); 
	session_destroy(); 
	
	if(isset($_GET['mode']))
	{
		if($_GET['mode'] == 1)
		{
			redirect("login","","","error=1");
		}
	}
	redirect();	
	
?>