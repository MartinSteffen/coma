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

	// Paper bewerten oder ablehnen, aber nicht mehr nach der Deadline der Konferenz

	// Alle Paper aller Konferenzen finden, für die der aktuelle Benutzer als Reviewer vorgesehen ist
	// und die die Deadline der Konferenz nicht überschreiten. Nur Paper anzeigen, die noch nicht
	// als "reviewed" markiert sind. Nur Paper anzeigen, die der Reviewer noch nicht bewertet hat (Summary ist leer)

   $SQL = "SELECT
   			reviewreport.reviewer_id, reviewreport.paper_id,
			paper.id, paper.state, paper.conference_id, paper.title,
			conference.id, conference.review_deadline, conference.name, reviewreport.summary
			FROM reviewreport
			  INNER JOIN paper ON (reviewreport.paper_id = paper.id)
			  INNER JOIN conference ON (paper.conference_id = conference.id)
			WHERE
			  (conference.review_deadline >= CURRENT_DATE)
			  AND (paper.state = 1)
			  AND (reviewreport.reviewer_id = ".$_SESSION['userID']." )
			  AND (reviewreport.summary IS NULL)";

	$result = mysql_query($SQL);
	$count = 0;
	while ($list = mysql_fetch_row ($result))
	{
		$task = array();
		$task[] = array("text"=>"In Conference", "action"=>$list[8]);
		$downloadLink = "<a href=\"index.php?m=reviewer&a=default&s=getfile&pid=$list[2]\" class=\"normal\">".$list[5]."</a>";
		$task[] = array("text"=>"Paper title", "action"=>$downloadLink);
		$task[] = array("text"=>"Deadline", "action"=>$list[7]);
		$reviewLink = "<a href=\"index.php?m=reviewer&a=review&s=review&paperID=$list[2]\" class=\"normal\">Review the paper.</a>";
		$task[] = array("text"=>"Task", "action"=>$reviewLink);
		$rejectLink = "<a href=\"index.php?m=reviewer&a=reject&paperID=$list[2]\" class=\"normal\">Reject the paper.</a>";
		$task[] = array("text"=>"Task", "action"=>$rejectLink);

		$tasks[$count] = $task;
		$count++;
	}


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

	//Find the new conferences that have no dates  ----------------------------------
	$SQL = "select conference.id, conference.name
			from conference,role
			where role.role_type = 2
			and role.state = 1
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = conference.id
			and (
			(conference.abstract_submission_deadline = '1970-01-01') OR
			(conference.paper_submission_deadline = '1970-01-01') OR
			(conference.review_deadline = '1970-01-01') OR
			(conference.final_version_deadline = '1970-01-01') OR
			(conference.notification = '1970-01-01') OR
			(conference.conference_start = '1970-01-01') OR
			(conference.conference_end = '1970-01-01') OR
			(conference.min_reviews_per_paper = '1970-01-01'))";

    $result=mysql_query($SQL);
    while ($list = mysql_fetch_row ($result))
	{
		$conferenceID = $list[0];
		$conferenceName = $list[1];

		    $task = array();
			$task[] = array("text"=>"Conference", "action"=>$conferenceName);
			$taskLink = "<a href=\"index.php?m=chair&a=conferences&s=conference&confID=$conferenceID\" class=\"normal\">This is a new conference. You must set the dates.</a>";
			$task[] = array("text"=>"Task", "action"=>$taskLink);
			$tasks[$count] = $task;
			$count++;
	}

	//Find the conferences that have no topics and/or no criterions  ----------------------------------
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
		//Look if it has criterions
		$SQL2 = "SELECT id FROM criterion WHERE conference_id = ".$conferenceID;
		$result2=mysql_query($SQL2);
		if (!($list2 = mysql_fetch_row ($result2)))
		{
		    $task = array();
			$task[] = array("text"=>"Conference", "action"=>$conferenceName);
			$taskLink = "<a href=\"index.php?m=chair&a=conferences&s=conference&confID=$conferenceID\" class=\"normal\">The conference has no criterions. You must add some.</a>";
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
	$SQL = "select role.role_type, conference.name from role, conference
			where role.state = 3
			and role.person_id = ".$_SESSION['userID']."
			and role.conference_id = conference.id";
	$count = 0;
    $result=mysql_query($SQL);
    while ($list = mysql_fetch_row ($result))
	{
	    $task = array();
	    if($list[0] == 2)
	    {
		$taskLink = "<a href=\"index.php?m=profile&a=roles\" class=\"normal\">You are invited as chair in conference ".$list[1].". You should accept or deny.</a>";
		$task[] = array("text"=>"Task", "action"=>$taskLink);
		$tasks[$count] = $task;
		$count++;
	    }
	    if($list[0] == 3)
	    {
		$taskLink = "<a href=\"index.php?m=profile&a=roles\" class=\"normal\">You are invited as reviewer in conference ".$list[1].". You should accept or deny.</a>";
		$task[] = array("text"=>"Task", "action"=>$taskLink);
		$tasks[$count] = $task;
		$count++;
	    }
	    if($list[0] == 4)
	    {
		$taskLink = "<a href=\"index.php?m=profile&a=roles\" class=\"normal\">You are invited as author in conference ".$list[1].". You should accept or deny.</a>";
		$task[] = array("text"=>"Task", "action"=>$taskLink);
		$tasks[$count] = $task;
		$count++;
	    }
	    if($list[0] == 5)
	    {
		$taskLink = "<a href=\"index.php?m=profile&a=roles\" class=\"normal\">You are invited as participant in conference ".$list[1].". You should accept or deny.</a>";
		$task[] = array("text"=>"Task", "action"=>$taskLink);
		$tasks[$count] = $task;
		$count++;
	    }
	}
	return $tasks;
}
?>