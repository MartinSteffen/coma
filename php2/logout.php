<?
	//Unset all session variables
	session_start();
	session_unset(); 
	session_destroy(); 
	
	//Go to the login site
	header("Location: login.php");
	exit;	
?>