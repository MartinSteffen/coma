<?
if(isset($_SESSION['userID']))
{
	template("WELCOME");
}
else redirect("logout",false,false,"error=1");	
?>