<?
$sql = new SQL();
$sql->connect();
if(isChair_Conference($_POST['confID']))
{
	if(isset($_POST['Submit']))
	{
		$confName = $_POST['confName'];
		$homepage = $_POST['homepage'];
		$description = $_POST['description'];
		if ($_POST['abstractSubmissionHidden'] == "")
		{
			$abstractSubmission = NULL;
		}
		else
		{
			$abstractSubmission = "'".$_POST['abstractSubmissionHidden']."'";
		}
		
		if ($_POST['paperSubmissionHidden'] == "")
		{
			$paperSubmission = NULL;
		}
		else
		{
			$paperSubmission = "'".$_POST['paperSubmissionHidden']."'";
		}
		
		if ($_POST['reviewSubmissionHidden'] == "")
		{
			$reviewSubmission = NULL;
		}
		else
		{
			$reviewSubmission = "'".$_POST['reviewSubmissionHidden']."'";
		}
		
		if ($_POST['finalVersionHidden'] == "")
		{
			$finalVersion = NULL;
		}
		else
		{
			$finalVersion = "'".$_POST['finalVersionHidden']."'";
		}
		
		if ($_POST['notificationHidden'] == "")
		{
			$notification = NULL;
		}
		else
		{
			$notification = "'".$_POST['notificationHidden']."'";
		}
		
		if ($_POST['conferenceStartHidden'] == "")
		{
			$conferenceStart = NULL;
		}
		else
		{
			$conferenceStart = "'".$_POST['conferenceStartHidden']."'";
		}
		
		if ($_POST['conferenceEndHidden'] == "")
		{
			$conferenceEnd = NULL;
		}
		else
		{
			$conferenceEnd = "'".$_POST['conferenceEndHidden']."'";
		}												

		$minimum = $_POST['minimum'];
		
		$SQL = "UPDATE conference 
		SET name = '$confName',
		homepage = '$homepage',
		description = '$description',
		abstract_submission_deadline = $abstractSubmission,
		paper_submission_deadline = $paperSubmission,
		review_deadline = $reviewSubmission,
		final_version_deadline = $finalVersion,
		notification = $notification,
		conference_start = $conferenceStart,
		conference_end = $conferenceEnd,
		min_reviews_per_paper = $minimum
		WHERE id = ".$_POST['confID'];
		
		$result=mysql_query($SQL);
	}
	redirect("chair","conferences","conference","confID=".$_POST['confID']);
}
else redirect("logout",false,false,"error=1");	
?>