<?  //Do not change please
if(isChair_Overall())
{
	$output = array();
	$tasks = array();
	$tasks = author_task();	
	$output[] = array("role"=>"author", "tasks"=>$tasks);

	$TPL['tasks'] = $output;		
	template("TASKS");
	$TPL['tasks'] = "";
}
else redirect("logout","","","mode=1");	
?>