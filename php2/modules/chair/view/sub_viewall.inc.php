<?
	$link = mysql_connect ("localhost","testUser","testPass");
	$base = mysql_select_db ("testComma");
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
	$output = array();
	$task = array();
	$count = 0;
    while ($list = mysql_fetch_row ($result)) 	
	{
		$task['confName'] = $list[4];
		$task['userName'] = $list[1]." ".$list[2]." ".$list[3];
		$task['paperName'] = $list[7];
		$task['paperID'] = $list[5];
		if($list[6]==0)
		{
			$task['text'] = "A new paper has arrived.";
		}
		if($list[6]==1)
		{
			$task['text'] = "The paper is being reviewed. Check if should be accepted.";
		}
		if($list[6]==2)
		{
			$task['text'] = "The paper is being reviewed and has <b>conflicts</b>.";
		}	
		$output[count] = $task;			
	}	

$TPL['chair'] = $output;		
template("CHAIR_viewall");
$TPL['chair'] = "";
?>