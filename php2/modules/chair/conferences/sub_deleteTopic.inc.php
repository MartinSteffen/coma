<?
if(isChair_Conference($_GET['confID']))
{
if(isChair_Topic($_GET['topicID']))
{
	$SQL = "DELETE FROM topic 
		    WHERE id = ".$_GET['topicID'];		
	$result=mysql_query($SQL);	

	redirect("chair","conferences","conference","confID=".$_GET['confID']);
}
else redirect("logout",false,false,"error=1");	
}
else redirect("logout",false,false,"error=1");	
?>
	