<?
if(isChair_Conference($_GET['confID']))
{
	$SQL = "SELECT name, homepage, description, abstract_submission_deadline, paper_submission_deadline,
			review_deadline, final_version_deadline, notification, conference_start, conference_end, min_reviews_per_paper
			FROM conference
			WHERE id = ".$_GET['confID'];

template("CHAIR_conference");
}
else redirect("logout",false,false,"error=1");	
?>