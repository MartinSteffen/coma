<?
$sql = new SQL();
$sql->connect();
if(isChair_Conference($_POST['confID']))
{
	if(isset($_POST['Submit']))
	{
		if((!($_POST['criterionName']=="")) && ($_POST['maxValue']>1) && ($_POST['qualityRating']>0))
		{
			$criterionName = $_POST['criterionName'];
			$criterionDesc = $_POST['criterionDesc'];
			$maxValue = $_POST['maxValue'];
			$qualityRating = $_POST['qualityRating'];
			$confID = $_POST['confID'];
			$SQL = "INSERT INTO criterion (conference_id, name, description, max_value, quality_rating) 
				    VALUES (".$confID." , '".$criterionName."' , '".$criterionDesc."' , ".$maxValue." , ".$qualityRating.")";
			
			$result=mysql_query($SQL);		
		}
	}
	redirect("chair","conferences","conference","confID=".$_POST['confID']);
}
else redirect("logout",false,false,"error=1");	
?>