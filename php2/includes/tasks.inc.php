<?
//Lib for the view of the tasks

// --------- FOR ADMIN ---------------------------
function admin_task()
{
	$tasks = array();
	
	/* SQL Query and results*/
		
	return $tasks;	
}

// --------- FOR AUTHOR --------------------------
function author_task()
{
	$tasks = array();	
	
	/* SQL Query and results*/
	
	return $tasks;	
}

// --------- FOR REVIEWER ------------------------
function reviewer_task()
{
	$tasks = array();	
	
	/* SQL Query and results*/
	
	return $tasks;	
}

// --------- FOR CHAIR ---------------------------
function chair_task()
{
	$tasks = array();	
	
	//Find the new papers -----------------------------------------------------------------------
	$SQL = "select person.id, person.title, person.first_name, person.last_name, 
			conference.name,
			paper.id, paper.state, paper.title
			from paper, conference, person, role 
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = conference.id 
			and conference.id = paper.conference_id 
			and paper.author_id = person.id  
			and paper.state = 0";
			
    $result=mysql_query($SQL);
	$count = 0;
    while ($list = mysql_fetch_row ($result)) 	
	{
	    $task = array();	
		$task[] = array("text"=>"In conference", "action"=>$list[4]);			
		$task[] = array("text"=>"Author", "action"=>$list[1]." ".$list[2]." ".$list[3]);
		$task[] = array("text"=>"Paper title", "action"=>$list[7]);
		$taskLink = "<a href=\"index.php?m=chair&a=papers&s=paper&paperID=$list[5]\" class=\"normal\">A new paper has arrived. You must send it to reviewers.</a>";		
		$task[] = array("text"=>"Task", "action"=>$taskLink);	
		$tasks[$count] = $task;
		$count++;		
	}
	
	//Find the papers that are totally reviewed ----------------------------------------------------
	$SQL1 = "select person.id, person.title, person.first_name, person.last_name, 
			conference.id, conference.name,
			paper.id, paper.title
			from paper, conference, person, role 
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = conference.id 
			and conference.id = paper.conference_id 
			and paper.author_id = person.id  
			and ((paper.state = 1) OR (paper.state=2))";
    $result1=mysql_query($SQL1);
    while ($list1 = mysql_fetch_row ($result1)) 	
	{
		//Check if is totally reviewed
		$conferenceID = $list1[4];
		$conferenceName = $list1[5];
		$authorID = $list1[0];
		$authorName = $list1[1]." ".$list1[2]." ".$list1[3];
		$paperID = $list1[6];
		$paperName = $list1[7];
		
		$isReviewed = true;
		
		$SQL2 = "SELECT count(id) FROM criterion WHERE conference_id = ".$conferenceID;
		$result2=mysql_query($SQL2);
		$criterionCount = 0;
		if($list2 = mysql_fetch_row ($result2))
		{
			$criterionCount = $list2[0];
		}
		
		$SQL2 = "SELECT reviewreport.id from reviewreport, paper 
				WHERE paper.id = ".$paperID."
				AND paper.id = reviewreport.paper_id";
		$result2=mysql_query($SQL2);		
		while ($list2 = mysql_fetch_row ($result2))
		{
			$reviewID = $list2[0];
			
			$SQL3 = "SELECT count(review_id) from rating
					 WHERE review_id = ".$reviewID;
			$result3=mysql_query($SQL3);
			$ratingCount = 0;
			if($list3 = mysql_fetch_row ($result3))
			{
				$ratingCount = $list3[0];
			}
			if(!($ratingCount == $criterionCount))
			{
				$isReviewed = false;
			}		
		}
		if($isReviewed == true)
		{
		    $task = array();	
			$task[] = array("text"=>"In conference", "action"=>$conferenceName);			
			$task[] = array("text"=>"Author", "action"=>$authorName);
			$task[] = array("text"=>"Paper title", "action"=>$paperName);
			$taskLink = "<a href=\"index.php?m=chair&a=papers&s=paper&paperID=$paperID\" class=\"normal\">The paper is reviewed. You must accept or reject it.</a>";		
			$task[] = array("text"=>"Task", "action"=>$taskLink);	
			$tasks[$count] = $task;
			$count++;			
		}
	}
	
	//Find the conferences that have no topics --------------------------------------------------------------
	$SQL = "select conference.id, conference.name 
			from conference,role 
			where role.role_type = 2
			and role.state = 1 
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = conference.id";
			
    $result=mysql_query($SQL);
    while ($list = mysql_fetch_row ($result)) 	
	{
		$conferenceID = $list[0];
		$conferenceName = $list[1];
		//Look if it has topics
		$SQL2 = "SELECT id FROM topic WHERE conference_id = ".$conferenceID;
		$result2=mysql_query($SQL2);
		if (!($list2 = mysql_fetch_row ($result2)))
		{
		    $task = array();	
			$task[] = array("text"=>"Conference", "action"=>$conferenceName);
			$taskLink = "<a href=\"index.php?m=chair&a=conferences&s=conference&confID=$conferenceID\" class=\"normal\">The conference has no topics. You must add some.</a>";		
			$task[] = array("text"=>"Task", "action"=>$taskLink);	
			$tasks[$count] = $task;
			$count++;							
		} 
	}			

	return $tasks;
}





// --------- FOR PARTICIPANT ---------------------------
function participant_task()
{
	$tasks = array();	
	
	//Find the roles where is invited -------------------
	$SQL = "select role_type from role 
			where state = 3 
			and person_id = ".$_SESSION['userID'];
	$count = 0;		
    $result=mysql_query($SQL);
    if ($list = mysql_fetch_row ($result)) 	
	{
	    $task = array();	
		$taskLink = "<a href=\"index.php?m=profile&a=roles\" class=\"normal\">You are invited to some roles. You should accept or deny.</a>";		
		$task[] = array("text"=>"Task", "action"=>$taskLink);	
		$tasks[$count] = $task;
		$count++;		
	}
	return $tasks;
}
?>