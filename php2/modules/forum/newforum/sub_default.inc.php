<?
$sql = new SQL();
$sql->connect();

if (isChair_Overall())
{
	$SQL = "INSERT INTO forum VALUES (0, ".$_POST['confID'].", '".$_POST['title']."', ".$_POST['type'].", DEFAULT)";

	$sql->insert($SQL);

	redirect('chair','conferences', false, false);

} else redirect(false, false, false, false);
?>