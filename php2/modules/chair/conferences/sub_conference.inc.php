<?
$sql = new SQL();
$sql->connect();
if(isChair_Conference($_GET['confID']))
{
	$SQL = "SELECT name, homepage, description, abstract_submission_deadline, paper_submission_deadline,
			review_deadline, final_version_deadline, notification, conference_start, conference_end, min_reviews_per_paper
			FROM conference
			WHERE id = ".$_GET['confID'];
    $result=mysql_query($SQL);
    $conference = array();
    while ($list = mysql_fetch_row ($result)) 	
    {
       $conference['confID'] = $_GET['confID'];
	   $conference['confName'] = $list[0];
	   $conference['homepage'] = $list[1];
	   $conference['description'] = $list[2];
	   if($list[3] == NULL)
	   {
			$conference['abstractSubmission'] = NULL;   
	   }
	   else
	   {
		   $conference['abstractSubmission'] = date("j F Y" , strtotime($list[3]));   
	   }
	   $conference['abstractSubmissionHidden'] = $list[3];
	   
	   if($list[4] == NULL)
	   {
			$conference['paperSubmission'] = NULL;   
	   }
	   else
	   {
		   $conference['paperSubmission'] = date("j F Y" , strtotime($list[4]));   
	   }
	   $conference['paperSubmissionHidden'] = $list[4];
	   
	   if($list[5] == NULL)
	   {
			$conference['reviewSubmission'] = NULL;   
	   }
	   else
	   {
		   $conference['reviewSubmission'] = date("j F Y" , strtotime($list[5]));   
	   }
	   $conference['reviewSubmissionHidden'] = $list[5];
	   
	   if($list[6] == NULL)
	   {
			$conference['finalVersion'] = NULL;   
	   }
	   else
	   {
		   $conference['finalVersion'] = date("j F Y" , strtotime($list[6]));  
	   }
	   $conference['finalVersionHidden'] = $list[6];
	   
	   if($list[7] == NULL)
	   {
			$conference['notification'] = NULL;   
	   }
	   else
	   {
		   $conference['notification'] = date("j F Y" , strtotime($list[7]));  
	   }
	   $conference['notificationHidden'] = $list[7];
	   
	   if($list[8] == NULL)
	   {
			$conference['conferenceStart'] = NULL;   
	   }
	   else
	   {
		   $conference['conferenceStart'] = date("j F Y" , strtotime($list[8]));   
	   }
	   $conference['conferenceStartHidden'] = $list[8];
	   
	   if($list[9] == NULL)
	   {
			$conference['conferenceEnd'] = NULL;   
	   }
	   else
	   {
		   $conference['conferenceEnd'] = date("j F Y" , strtotime($list[9]));   
	   }
	   $conference['conferenceEndHidden'] = $list[9];	   	   	   	   	   	   

	   $conference['minimum'] = $list[10];
    }
	
	
	//Get the topics
	$SQL = "SELECT id, name from topic WHERE conference_id = ".$_GET['confID'];
    $result=mysql_query($SQL);
    $topics = array();
	$count = 0;
    while ($list = mysql_fetch_row ($result)) 	
    {
		$topic = array();
		$topic['topicID'] = $list[0];
		$topic['topicName'] = $list[1];
		$topics[$count] = $topic;
		$count++;
	}
	
	//Get the criterions
	$SQL = "SELECT id, name, description, max_value, quality_rating 
			FROM criterion 
			WHERE conference_id = ".$_GET['confID'];
    $result=mysql_query($SQL);
    $criterions = array();
	$count = 0;
    while ($list = mysql_fetch_row ($result)) 	
    {
		$criterion = array();
		$criterion['criterionID'] = $list[0];
		$criterion['criterionName'] = $list[1];
		$criterion['criterionDesc'] = $list[2];		
		$criterion['maxValue'] = $list[3];
		$criterion['qualityRating'] = $list[4];
		$criterions[$count] = $criterion;
		$count++;
	}
	
	
	//Get the forums
	$SQL = "SELECT title, forum_type
		    FROM forum 
			WHERE conference_id = ".$_GET['confID']."
			AND ((forum_type = 1) OR (forum_type=2))";
    $result=mysql_query($SQL);
    $forums = array();
	$count = 0;
    while ($list = mysql_fetch_row ($result)) 	
    {
		$forum = array();
		$forum['title'] = $list[0];
		$forum_type = $list[1];
		if($forum_type == 1)
		{
			$forum['forum_type'] = "Open forum";
		} 
		else
		{
			$forum['forum_type'] = "Comittee forum";
		}
		$forums[$count] = $forum;
		$count++;
	}
			
	$output = array();
	$output['conference'] = $conference;				
	$output['topics'] = $topics;
	$output['criterions'] = $criterions;
	$output['forums'] = $forums;

$TPL['chair'] = $output;
template("CHAIR_conference");
}
else redirect("logout",false,false,"error=1");	
?>