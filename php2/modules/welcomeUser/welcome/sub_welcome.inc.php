<?
$sql = new SQL();
$sql->connect();
if(isset($_SESSION['userID']))
{
	template("WELCOME");
}
else redirect("logout",false,false,"error=1");	
?>