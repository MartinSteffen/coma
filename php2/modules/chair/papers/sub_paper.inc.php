<?
if(isChair_Paper($_GET['paperID']))
{
	$SQL = "select paper.title, paper.abstract, paper.last_edited, paper.version, paper.state, 
	        person.id, person.title, person.first_name, person.last_name, 
			conference.id, conference.name
			from paper, conference, person, role 
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = conference.id 
			and conference.id = paper.conference_id 
			and paper.id = ".$_GET['paperID']." 
			and paper.author_id = person.id";
					
    $result=mysql_query($SQL);
	$output = array();
	$paper = array();
	$count = 0;	
    if ($list = mysql_fetch_row ($result)) 	
	{	
		$paper['paperID'] = $_GET['paperID'];
		$paper['paperName'] = $list[0];
		$paper['paperDesc'] = $list[1];	
		$paper['lastEdited'] = date("j F Y" , strtotime($list[2]));
		$paper['version'] = $list[3];
		$paper['stateID'] = $list[4];
		if($list[4]==0)
		{
			$paper['state'] = "Open";
			$paper['class'] = "textGreen";
		}
		if($list[4]==1)
		{
			$paper['state'] = "Being reviewed";
			$paper['class'] = "textGreen";
		}
		if($list[4]==2)
		{
			$paper['state'] = "Being reviewed, conflicting";
			$paper['class'] = "textRed";
		}		
		if($list[4]==3)
		{
			$paper['state'] = "Accepted";
			$paper['class'] = "textGreen";
		}
		if($list[4]==4)
		{
			$paper['state'] = "Rejected";
			$paper['class'] = "textRed";
		}	
		$paper['authorID'] = $list[5];
		$paper['authorName'] = $list[6]." ".$list[7]." ".$list[8];
		$paper['confID'] = $list[9];
		$paper['confName'] = $list[10];
		
		$SQL2 = "SELECT topic.id, topic.name from topic, isabouttopic
				WHERE isabouttopic.paper_id = ".$_GET['paperID']."
				AND isabouttopic.topic_id = topic.id";

		$topicCount = 0;
		$topics = array();
		$result2=mysql_query($SQL2);				
		while ($list2 = mysql_fetch_row ($result2))
		{
			$topic = array();
			$topic['topicID'] = $list2[0];
			$topic['topicName'] = $list2[1];
			$topics[$topicCount] = $topic;
			$topicCount++;
		}
		$paper['topics'] = $topics;		
	}
	
$output['paper'] = $paper;

// ----import the Rating-To-Program-Algorithm 		
	

// Get the review report

	$report = array();
	$report['isReviewed'] = true;
	$report['totalGrade'] = 0;
	$report['totalGradeColor'] = "textGreen";
	
	$SQL = "SELECT criterion.quality_rating FROM criterion, paper
			WHERE paper.id = ".$_GET['paperID']."
			AND paper.conference_id = criterion.conference_id";
    $result=mysql_query($SQL);
	$max_quality = 0;
	$CriterionCount = 0;
    while ($list = mysql_fetch_row ($result)) 	
	{
		$max_quality = $max_quality + $list[0];
		$CriterionCount++;
	}		
			
	$SQL1 = "SELECT reviewreport.id, person.id, person.title, person.first_name, person.last_name, reviewreport.summary, reviewreport.remarks, reviewreport.confidential
			FROM reviewreport, person
			WHERE reviewreport.paper_id = ".$_GET['paperID']."
			AND reviewreport.reviewer_id = person.id";
    $result1=mysql_query($SQL1);
	$revCount = 0;
	$reviewers = array();
	$reviewerCount = 0;
    while ($list1 = mysql_fetch_row ($result1)) 	
	{
		$reviewer = array();
		$reviewID = $list1[0];
		$reviewer['reviewerID'] = $list1[1];
		$reviewer['reviewerName'] = $list1[2]." ".$list1[3]." ".$list1[4];
		$reviewer['summary'] = $list1[5];
		$reviewer['remarks'] = $list1[6];
		$reviewer['confidential'] = $list1[7];
		
		$SQL2 = "SELECT criterion.name, criterion.quality_rating, rating.grade, rating.comment, criterion.max_value FROM criterion, rating
			     WHERE rating.review_id = ".$reviewID." 
				 AND rating.criterion_id = criterion.id";
	    $result2=mysql_query($SQL2);
		$ratings = array();
		$ratingCount = 0;
		$totalGrade = 0;
	    while ($list2 = mysql_fetch_row ($result2)) 	
		{
			$rating = array();
			$rating['criterionName'] = $list2[0];
			$rating['qualityRating'] = $list2[1]/$max_quality*100;
			$rating['grade'] = $list2[2]/$list2[4]*100;
			$totalGrade = $totalGrade + ($rating['grade']*$rating['qualityRating']);
			$rating['comment'] = $list2[3];				 
			$ratings[$ratingCount] = $rating;
			$ratingCount++;
		}
		if($CriterionCount == $ratingCount)
		{
			$reviewer['totalGrade'] = ($totalGrade/100)."%";
			$reviewer['totalGradeColor'] = "textGreen";
			$report['totalGrade'] = $report['totalGrade'] + $totalGrade/100;
		}
		else
		{
			$reviewer['totalGrade'] = "No total grade yet.";
			$reviewer['totalGradeColor'] = "textRed";
			$report['isReviewed'] = false;											
		}			
		$reviewer['ratings'] = $ratings;
		$reviewers[$reviewerCount] = $reviewer;
		$reviewerCount++;				
	}
	if($reviewerCount == 0)
	{
		$report['isReviewed'] = false;
	}
	if($report['isReviewed'] == false)
	{
		$report['totalGrade'] = "No total grade yet.";
		$report['totalGradeColor'] = "textRed";		
	}
	else
	{  	
		$report['totalGrade'] = ($report['totalGrade'] / $reviewerCount)."%";	
	}
	$report['reviewers'] = $reviewers;
	
	$output['report'] = $report;
	
$TPL['chair'] = $output;
template("CHAIR_paper");
$TPL['chair'] = "";
}
else redirect("logout",false,false,"error=1");	
?>