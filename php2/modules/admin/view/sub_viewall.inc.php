<?
if(isChair_Overall())
{
	$output = array();
	$tasks = array();
	$tasks = admin_task();	
	$output[] = array("role"=>"admin", "tasks"=>$tasks);

	$TPL['tasks'] = $output;		
	template("TASKS");
	$TPL['tasks'] = "";
}
else redirect("logout","","","mode=1");	
?>