<?
$sql = new SQL();
$sql->connect();
if(isReviewer_Overall())
{
	$SQL =
	  "DELETE FROM reviewreport
	  WHERE (paper_id = ".$_GET['paperID'].")
	  AND (reviewer_id = ".$_SESSION['userID'].")";

	$result = mysql_query($SQL);

	$SQL =
	  "INSERT INTO rejectedpapers VALUES (".$_SESSION['userID'].",".$_GET['paperID'].")";

	echo $SQL;

	$result = mysql_query($SQL);

  redirect("tasks",false,false,false);
} else redirect("logout",false,false,"error=1");
?>