<?
if(isChair_Conference($_POST['confID']))
{
	if(isset($_POST['Submit']))
	{
		$confName = $_POST['confName'];
		$homepage = $_POST['homepage'];
		$description = $_POST['description'];
		$abstractSubmission = $_POST['abstractSubmissionHidden'];
		$paperSubmission = $_POST['paperSubmissionHidden'];
		$reviewSubmission = $_POST['reviewSubmissionHidden'];
		$finalVersion = $_POST['finalVersionHidden'];
		$notification = $_POST['notificationHidden'];
		$conferenceStart = $_POST['conferenceStartHidden'];
		$conferenceEnd = $_POST['conferenceEndHidden'];
		
		$SQL = "UPDATE conference 
		SET name = '$confName',
		homepage = '$homepage',
		description = '$description',
		abstract_submission_deadline = '$abstractSubmission',
		paper_submission_deadline = '$paperSubmission',
		review_deadline = '$reviewSubmission',
		final_version_deadline = '$finalVersion',
		notification = '$notification',
		conference_start = '$conferenceStart',
		conference_end = '$conferenceEnd',
		min_reviews_per_paper = $minimum
		WHERE id = ".$_POST['confID'];
		
		$result=mysql_query($SQL);
	}
	redirect("chair","conferences","conference","confID=".$_POST['confID']);
}
else redirect("logout",false,false,"error=1");	
?>