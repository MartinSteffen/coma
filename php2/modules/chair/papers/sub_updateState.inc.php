<?
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
		$SQL = "SELECT id FROM reviewreport WHERE paper_id = ".$_POST['paperID'];
		$result=mysql_query($SQL);	
		if ($list = mysql_fetch_row ($result)) 	
		{
			$stateID = 1;
		}
		else
		{
			$stateID = 0;
		}
	}		
	$SQL = "update paper set state = ".$stateID." where id = ".$_POST['paperID'];
	$result=mysql_query($SQL);	
	redirect("chair","papers","paper","paperID=".$_POST['paperID']);
}
else redirect("","","","logout=1");		
?>	