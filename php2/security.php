<?
	session_start();
	//PHP Script in order to make the pages secure.
	//For every script the user name and password are beeing checked.
		
	if(isset($_SESSION['userID']) && isset($_SESSION['userName']) && isset($_SESSION['userPassword']))		
	{
		//DEBUG TEST-------------------------------------------------------------
		if($_SESSION['userName']=="ivan" && $_SESSION['userPassword']=="ivan")
		{
		}
		else
		{	
			header("Location: logout.php");
			exit;		
		}
		//END DEBUG TEST---------------------------------------------------------
	}
	else
	{
		header("Location: logout.php");
		exit;	
	}
?>