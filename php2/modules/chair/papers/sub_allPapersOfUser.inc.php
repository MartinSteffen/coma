<?
$sql = new SQL();
$sql->connect();
if(isChair_Person($_GET['userID']))
{
	$SQL = "select title, first_name, last_name from person where id = ".$_GET['userID'];
    $result=mysql_query($SQL);
	
	$papers = array();
	$output = array();
	$output['userID'] = $_GET['userID'];
    if ($list = mysql_fetch_row ($result)) 	
    {
		$output['userName'] = $list[0]." ".$list[1]." ".$list[2];   
    }	
	
	$SQL = "select paper.id, paper.title, paper.abstract, paper.state, conference.id, conference.name 
			from paper, conference  
			where conference.id = paper.conference_id 
			and paper.author_id = ".$_GET['userID'];

    $result=mysql_query($SQL);
	$count = 0;	
    while ($list = mysql_fetch_row ($result)) 	
	{
		$paper = array();
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
		$paper['confID'] = $list[4];
		$paper['confName'] = $list[5];

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
template("CHAIR_allPapersOfUser");
$TPL['chair'] = "";
}
else redirect("logout",false,false,"error=1");	
?>