<?
if(isset($_SESSION['userID']))
{	
	template("PROFILE_default");
}
else redirect("logout","","","mode=1");	
?>