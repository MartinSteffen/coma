<?
$sql = new SQL();
$sql->connect();
if(isReviewer_Overall())
{
	$SQL = "DELETE FROM deniespaper WHERE (person_id='".$_SESSION['userID']."') AND (paper_id='".$_POST['paperlist']."')";
	$result = mysql_query($SQL);
	redirect("reviewer","denied",false,false);
} else redirect("logout",false,false,"error=1");
?>