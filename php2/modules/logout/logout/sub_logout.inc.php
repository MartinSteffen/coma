<?
	//Destroy the session
	session_unset(); 
	session_destroy(); 
	if(isset($error))
	{
		if($error == 1)
		{
			redirect("login",false,false,"error=1");
		}
		if($error == 0)
		{
			redirect("login");
		}
	}
	else
	{
		redirect("login");
	}
	
?>	