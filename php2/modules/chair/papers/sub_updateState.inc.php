<?
$sql = new SQL();
$sql->connect();
if(isChair_Paper($_POST['paperID']))
{
	$stateID = 0;
	if(isset($_POST['accept']))
	{
		$stateID = 3;
	}
	if(isset($_POST['reject']))
	{
		$stateID = 4;
	}	
	if(isset($_POST['reset']))
	{
		makePaperState($_POST['paperID']);
	}
	redirect("chair","papers","paper","paperID=".$_POST['paperID']);
}
else redirect("logout",false,false,"error=1");	
?>	