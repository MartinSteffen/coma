<?
if(isChair_Topic($_GET['topicID']))
{
	$SQL = "select name from topic where id = ".$_GET['topicID'];
    $result=mysql_query($SQL);
    $output = array();
	$papers = array();
	$output['topicID'] = $_GET['topicID'];
    if ($list = mysql_fetch_row ($result)) 	
    {
		$output['topicName'] = $list[0];  
    }
	$SQL = "select paper.id, paper.title, paper.abstract, paper.state, 
	        person.id, person.title, person.first_name, person.last_name, conference.name 
			from paper, person, role, conference, isabouttopic  
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = paper.conference_id 
			and paper.author_id = person.id
			and paper.conference_id = conference.id
			and paper.id = isabouttopic.paper_id
			and isabouttopic.topic_id = ".$_GET['topicID'];
						
    $result=mysql_query($SQL);
	$count = 0;	
    while ($list = mysql_fetch_row ($result)) 	
	{
		$paper = array ();
		$paper['paperID'] = $list[0];
		$paper['paperName'] = $list[1];
		$paper['paperDesc'] = $list[2];	
		if($list[3]==0)
		{
			$paper['state'] = "Open";
			$paper['class'] = "textGreen";
		}
		if($list[3]==1)
		{
			$paper['state'] = "Being reviewed";
			$paper['class'] = "textGreen";
		}
		if($list[3]==2)
		{
			$paper['state'] = "Being reviewed, conflicting";
			$paper['class'] = "textRed";
		}		
		if($list[3]==3)
		{
			$paper['state'] = "Accepted";
			$paper['class'] = "textGreen";
		}
		if($list[3]==4)
		{
			$paper['state'] = "Rejected";
			$paper['class'] = "textRed";
		}			
		$paper['authorID'] = $list[4];
		$paper['authorName'] = $list[5]." ".$list[6]." ".$list[7];
		$paper['confName'] = $list[8];		
		$papers[$count] = $paper;		
		$count = $count + 1;
	}

$output['papers'] = $papers;
$TPL['chair'] = $output;
template("CHAIR_allPapersOfTopic");
$TPL['chair'] = "";
}
else redirect("logout",false,false,"error=1");	
?>