<?
$sql = new SQL();
$sql->connect();

if (isChair_Overall())
{
	$SQL = "INSERT INTO forum VALUES (0, ".$_POST['confID'].", '".$_POST['title']."', ".$_POST['type'].", DEFAULT)";

	$sql->insert($SQL);
echo "DEBUGGING:
confID = ".$_POST['confID']." ,
title = ".$_POST['title']." ,
type = ".$_POST['type'];
	redirect('chair','conferences', 'conference', 'confID='.$_POST['confID']);

} else redirect(false, false, false, false);
?>