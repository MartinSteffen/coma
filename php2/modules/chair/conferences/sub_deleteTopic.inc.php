<?
if(isChair_Conference($_GET['confID']))
{
if(isChair_Topic($_GET['topicID']))
{
	//Delete all papers in the topic	
	$SQL = "SELECT paper.id FROM paper, isabouttopic 
		WHERE isabouttopic.topic_id = ".$_GET['topicID']."
		and isabouttopic.paper_id = paper.id";		
	$result=mysql_query($SQL);	
	while ($list = mysql_fetch_row ($result)) 					
	{
		$SQL2 = "DELETE FROM paper WHERE id = ".$list[0];
		$result2=mysql_query($SQL2);	
	}
	
	//Delete the topic
	$SQL = "DELETE FROM topic 
		WHERE id = ".$_GET['topicID'];		
	$result=mysql_query($SQL);	

	redirect("chair","conferences","conference","confID=".$_GET['confID']);
}
else redirect("logout",false,false,"error=1");	
}
else redirect("logout",false,false,"error=1");	
?>
	