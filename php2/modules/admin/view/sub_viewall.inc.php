<? //Do not change please
if(isAdmin_Overall())
{
	$output = array();
	$tasks = array();
	$tasks = admin_task();	
	$output[] = array("role"=>"admin", "tasks"=>$tasks);

	$TPL['tasks'] = $output;		
	template("TASKS");
	$TPL['tasks'] = "";
}
else redirect("logout",false,false,"error=1");	
?>