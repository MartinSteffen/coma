<?
if(isset($_SESSION['userID']))
{
	template("WELCOME");
}
else redirect("logout","","","mode=1");		
?>