<?
if(isChair_Conference($_POST['confID']))
{
if(isChair_Topic($_POST['topicID']))
{
	if(isset($_POST['Submit']))
	{
		$topicName = $_POST['topicName'];
		
		$SQL = "UPDATE topic 
		SET name = '$topicName'
		WHERE id = ".$_POST['topicID'];
		
		$result=mysql_query($SQL);		
	}
	redirect("chair","conferences","conference","confID=".$_POST['confID']);
}
else redirect("logout",false,false,"error=1");	
}
else redirect("logout",false,false,"error=1");	
?>
	