<?
$sql = new SQL();
$sql->connect();
if(isChair_Conference($_GET['confID']))
{
	$SQL = "select name from conference where id = ".$_GET['confID'];
    $result=mysql_query($SQL);
    $output = array();
	$papers = array();
	$output['confID'] = $_GET['confID'];
    if ($list = mysql_fetch_row ($result)) 	
    {
		$output['confName'] = $list[0];  
    }
	$SQL = "select paper.id, paper.title, paper.abstract, paper.state, 
	        person.id, person.title, person.first_name, person.last_name 
			from paper, person
			where paper.conference_id = ".$_GET['confID']." 
			and paper.author_id = person.id";
						
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

		$SQL2 = "SELECT topic.name from topic, isabouttopic
				WHERE isabouttopic.paper_id = ".$list[0]."
				AND isabouttopic.topic_id = topic.id";

		$topicCount = 0;
		$paper['topicName'] = "";
		$result2=mysql_query($SQL2);				
		while ($list2 = mysql_fetch_row ($result2))
		{
			if ($topicCount > 0)
			{
				$paper['topicName'] .= ", ";
			}
			$paper['topicName'] .= $list2[0]; 
			$topicCount++;
		} 						
		$papers[$count] = $paper;		
		$count = $count + 1;
	}

$output['papers'] = $papers;
$TPL['chair'] = $output;
template("CHAIR_allPapersOfConference");
$TPL['chair'] = "";
}
else redirect("logout",false,false,"error=1");	
?>