<?
$sql = new SQL();
$sql->connect();
if(isChair_Conference($_POST['confID']))
{
	if(isset($_POST['Submit']))
	{
		if((!($_POST['criterionName']=="")) && (!($_POST['maxValue']=="")) && (!($_POST['qualityRating']=="")))
		{	
			$criterionName = $_POST['criterionName'];
			$criterionDesc = $_POST['criterionDesc'];
			$maxValue = $_POST['maxValue'];
			$qualityRating = $_POST['qualityRating'];
		
			$SQL = "UPDATE criterion 
			SET name = '$criterionName',
			description = '$criterionDesc',
			max_value = $maxValue,
			quality_rating = $qualityRating
			WHERE id = ".$_POST['criterionID'];

			$result=mysql_query($SQL);
		}		
	}
	redirect("chair","conferences","conference","confID=".$_POST['confID']);
}
else redirect("logout",false,false,"error=1");		
?>
	