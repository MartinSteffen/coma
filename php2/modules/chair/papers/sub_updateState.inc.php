<?
$sql = new SQL();
$sql->connect();
if(isChair_Paper($_POST['paperID']))
{
	if(isset($_POST['reset']))
	{
		makePaperState($_POST['paperID']);
	}
	else
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
		$SQL = "update paper set state = ".$stateID." where id = ".$_POST['paperID'];
		$result=mysql_query($SQL);	
	}
	redirect("chair","papers","paper","paperID=".$_POST['paperID']);
}
else redirect("logout",false,false,"error=1");	
?>	