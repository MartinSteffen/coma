<?
$sql = new SQL();
$sql->connect();
if(isReviewer_Overall())
{
	$SQL = "INSERT INTO preferspaper VALUES ('".$_SESSION['userID']."','".$_POST['paper']."')";
	$result = mysql_query($SQL);
	redirect("tasks",false,false,false);
} else redirect("logout",false,false,"error=1");
?>