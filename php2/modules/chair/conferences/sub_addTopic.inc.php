<?
$sql = new SQL();
$sql->connect();
if(isChair_Conference($_POST['confID']))
{
	if(isset($_POST['Submit']))
	{
		if(!($_POST['topicName']==""))
		{
			$topicName = $_POST['topicName'];
			$confID = $_POST['confID'];
			$SQL = "INSERT INTO topic (name, conference_id) VALUES ('".$topicName."', ".$confID.")";
			
			$result=mysql_query($SQL);	
		}
	}
	redirect("chair","conferences","conference","confID=".$_POST['confID']);
}
else redirect("logout",false,false,"error=1");	
?>
	