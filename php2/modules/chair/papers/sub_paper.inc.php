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
		$paper['lastEdited'] = date("j F Y, g:i a" , strtotime($list[2]));
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
	}
	
$output['paper'] = $paper;

// ---------- Calculate the Grade --------------------------------
	
	$SQL = "SELECT criterion.max_value FROM criterion, paper
			WHERE criterion.conference_id = paper.conference_id
			AND paper.id = ".$_GET['paperID'];
	$result=mysql_query($SQL);
	$max_grade = 0;
	$citerion_count = 0;
    while ($list = mysql_fetch_row ($result)) 	
	{
		$max_grade = $list[0];
		$criterion_count++;
	}
	
	$SQL = "SELECT reviewer_id FROM reviewreport WHERE paper_id = ".$_GET['paperID'];
	$result=mysql_query($SQL);
	$reviewer_count = 0;
    while ($list = mysql_fetch_row ($result)) 	
	{
		$reviewer_count++;
	}
	
	$SQL = "SELECT grade from rating, reviewreport
		    WHERE reviewreport.paper_id = ".$_GET['paperID']." 
			AND reviewreport.id = rating.review_id"; 
	$result=mysql_query($SQL);
	$grades_count = 0;
    while ($list = mysql_fetch_row ($result)) 	
	{
		$grades_count++;
	}
	$isReviewed = false;
	$grade = 0;
	if($grades_count == ($criterion_count*$reviewer_count))
	{
		$isReviewed = true;
		if(!(reviewer_count == 0))
		{
			$grade = ()/
		}
	}		
	
	
	
$TPL['chair'] = $output;
template("CHAIR_paper");
$TPL['chair'] = "";
}
else redirect("logout","","","mode=1");		
?>