<?
$sql = new SQL();
$sql->connect();
if(isset($_SESSION['userID']))
{	
	template("PROFILE_default");
}
else redirect("logout",false,false,"error=1");	
?>