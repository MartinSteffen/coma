<?
if(isChair_Paper($_GET['paperID']))
{
	if(isChair_Person($_GET['reviewerID']))
	{
	$SQL = "SELECT id FROM reviewreport 
			WHERE paper_id = ".$_GET['paperID']."
			and reviewer_id = ".$_GET['reviewerID'];
	$result=mysql_query($SQL);	
	while ($list = mysql_fetch_row ($result)) 	
	{
		$reviewID = $list[0];
		$SQL2 = "DELETE FROM rating where review_id = ".$reviewID;
		$result2=mysql_query($SQL2);	
	}
	
	
	
	$SQL = "DELETE FROM reviewreport 
			WHERE paper_id = ".$_GET['paperID']."
			and reviewer_id = ".$_GET['reviewerID'];	
	$result=mysql_query($SQL);	
	
	
	
	$SQL = "SELECT id FROM reviewreport WHERE paper_id = ".$_GET['paperID'];
	$result=mysql_query($SQL);	
	if ($list = mysql_fetch_row ($result)) 	
	{
		$stateID = 1;
	}
	else
	{
		$stateID = 0;
	}	

	$SQL = "update paper set state = ".$stateID." where id = ".$_GET['paperID'];
	$result=mysql_query($SQL);	
	redirect("chair","papers","paper","paperID=".$_GET['paperID']);
	}
	else redirect("logout",false,false,"error=1");	
}
else redirect("logout",false,false,"error=1");	
?>	