<?
$sql = new SQL();
$sql->connect();
if(isChair_Conference($_GET['confID']))
{
	$SQL = "DELETE FROM criterion 
		    WHERE id = ".$_GET['criterionID'];		
	$result=mysql_query($SQL);	

	redirect("chair","conferences","conference","confID=".$_GET['confID']);
}
else redirect("logout",false,false,"error=1");		
?>
	