<?  //Do not change please
if(isChair_Overall())
{
	$output = array();
	$tasks = array();
	$tasks = reviewer_task();	
	$output[] = array("role"=>"reviewer", "tasks"=>$tasks);

	$TPL['tasks'] = $output;		
	template("TASKS");
	$TPL['tasks'] = "";
}
else redirect("logout","","","mode=1");	
?>