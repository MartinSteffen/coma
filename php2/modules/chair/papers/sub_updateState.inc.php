<?
if(isChair_Paper($_POST['paperID']))
{
	$SQL = "update paper set state = ".$_POST['stateID']." where id = ".$_POST['paperID'];
	$result=mysql_query($SQL);	
	redirect("chair","papers","paper","paperID=".$_POST['paperID']);
}
else redirect("logout","","","mode=1");			
?>	