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
			and not (paper.state = 3)
			and not (paper.state = 4)";
			
    $result=mysql_query($SQL);
	$count = 0;
    while ($list = mysql_fetch_row ($result)) 	
	{
	    $task = array();	
		$task[] = array("text"=>"In conference", "action"=>$list[4]);			
		$task[] = array("text"=>"Author", "action"=>$list[1]." ".$list[2]." ".$list[3]);
		$task[] = array("text"=>"Paper title", "action"=>$list[7]);
		if($list[6]==0)
		{	
			$taskLink = "<a href=\"index.php?m=chair&a=papers&s=paper&paperID=$list[5]\" class=\"normal\">A new paper has arrived.</a>";		
		}
		if($list[6]==1)
		{
			$taskLink = "<a href=\"index.php?m=chair&a=papers&s=paper&paperID=$list[5]\" class=\"normal\">The paper is being reviewed. Check if should be accepted.</a>";
		}
		if($list[6]==2)
		{
			$taskLink = "<a href=\"index.php?m=chair&a=papers&s=paper&paperID=$list[5]\" class=\"normal\">The paper is being reviewed and has <b>conflicts</b>.</a>";		
		}
		$task[] = array("text"=>"Task", "action"=>$taskLink);	
		$tasks[$count] = $task;
		$count++;		
	}
	return $tasks;
}

?>