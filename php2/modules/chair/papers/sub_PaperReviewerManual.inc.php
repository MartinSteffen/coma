<?
if(isChair_Paper($_GET['paperID']))
{
	$SQL = "SELECT conference.min_reviews_per_paper FROM conference, paper
			WHERE paper.id = ".$_GET['paperID']."
			AND paper.conference_id = conference.id";
    $result=mysql_query($SQL);			
	$minimum = 0;
	if ($list = mysql_fetch_row ($result))
	{
		$minimum = $list[0];
	}
	
	$SQL = "SELECT person.id, person.title, person.first_name, person.last_name
		  FROM person, role, paper
		  WHERE role.role_type = 3
		  AND role.state = 1
		  AND role.person_id = person.id
		  AND role.conference_id = paper.conference_id
		  AND paper.id = ".$_GET['paperID'];
	$reviewers = array();
	$count = 0;
	$result=mysql_query($SQL);
	while ($list = mysql_fetch_row ($result))
	{
		$reviewer = array();
		$reviewer['reviewerID'] = $list[0];
		$reviewer['reviewerName'] = $list[1]." ".$list[2]." ".$list[3];
		
		$SQL2 = "SELECT * FROM deniespaper 
				 WHERE person_id = ".$list[0]."
				 AND paper_id = ".$_GET['paperID'];
		$result2=mysql_query($SQL2);
		if (!($list2 = mysql_fetch_row ($result2)))
		{
			$SQL3 = "SELECT * FROM excludespaper 
					 WHERE person_id = ".$list[0]."
					 AND paper_id = ".$_GET['paperID'];
			$result3=mysql_query($SQL3);
			if (!($list3 = mysql_fetch_row ($result3)))
			{
				$SQL4 = "SELECT * FROM reviewreport 
						 WHERE reviewer_id = ".$list[0]."
						 AND paper_id = ".$_GET['paperID'];
				$result4=mysql_query($SQL4);
				if (!($list4 = mysql_fetch_row ($result4)))
				{
					$reviewers[$count] = $reviewer;
					$count++;			
				}			
			}		
		}				 
	}
	
	$SQL = "SELECT count(reviewer_id) FROM reviewreport
			WHERE paper_id = ".$_GET['paperID'];
	$result=mysql_query($SQL);
	if ($list = mysql_fetch_row ($result))
	{
		$alreadyReviewers = $list[0];
	}
	$mustChoose = 0;
	if($alreadyReviewers<$minimum)
	{
		$mustChoose = $minimum-$alreadyReviewers;
	}
	$output['mustChoose'] = $mustChoose;
	$output['alreadyRev'] = $alreadyReviewers;
	$output['minimum'] = $minimum;
	$output['reviewers'] = $reviewers;
	$output['paperID'] = $paperID;
	$TPL['chair'] = $output;

	template("CHAIR_manual");	

}
else redirect("logout",false,false,"error=1");	
?>