<?
$sql = new SQL();
$sql->connect();

if (isChair_Overall())
{
	$SQL = "INSERT INTO forum VALUES (0, ".$_POST['confID'].", '".$_POST['title']."', ".$_POST['type'].", DEFAULT)";

	// $sql->insert($SQL);

	template("FORUM_viewforum");
} else redirect(false, false, false, false);
?>