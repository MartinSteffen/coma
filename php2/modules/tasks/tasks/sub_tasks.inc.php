<?
if(isset($_SESSION['userID']))
{
	$output = array();
	
	if(isAdmin_Overall())
	{
		$tasks = array();
		$tasks = admin_task();	
		$output[] = array("role"=>"admin", "tasks"=>$tasks);	
	}

	if(isChair_Overall())
	{	
		$tasks = array();
		$tasks = chair_task();	
		$output[] = array("role"=>"chair", "tasks"=>$tasks);
	}
	
	if(isReviewer_Overall())
	{	
		$tasks = array();
		$tasks = reviewer_task();	
		$output[] = array("role"=>"reviewer", "tasks"=>$tasks);		
	}
	
	if(isAuthor_Overall())
	{	
		$tasks = array();
		$tasks = author_task();	
		$output[] = array("role"=>"author", "tasks"=>$tasks);	
	}
		
	$TPL['tasks'] = $output;		
	template("TASKS");
	$TPL['tasks'] = "";	
}
else redirect("logout","","","mode=1");	
?>