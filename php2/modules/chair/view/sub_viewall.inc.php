<?
if(isChair_Overall())
{
	$output = array();
	$tasks = array();
	$tasks = chair_task();	
	$output[] = array("role"=>"chair", "tasks"=>$tasks);

	$TPL['tasks'] = $output;		
	template("TASKS");
	$TPL['tasks'] = "";
}
else redirect("","","","logout=1");	
?>