<?
if(isset($_SESSION['userID']))
{
	$output = array();
	
	$tasks = array();
	$tasks = admin_task();	
	$output[] = array("role"=>"admin", "tasks"=>$tasks);	

	$tasks = array();
	$tasks = chair_task();	
	$output[] = array("role"=>"chair", "tasks"=>$tasks);
	
	$tasks = array();
	$tasks = reviewer_task();	
	$output[] = array("role"=>"reviewer", "tasks"=>$tasks);		
	
	$tasks = array();
	$tasks = author_task();	
	$output[] = array("role"=>"author", "tasks"=>$tasks);	
		
	$TPL['tasks'] = $output;		
	template("TASKS");
	$TPL['tasks'] = "";	
}
else redirect("logout",false,false,"error=1");	
?>