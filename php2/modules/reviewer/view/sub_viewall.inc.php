<?  //Do not change please
$sql = new SQL();
$sql->connect();
if(isReviewer_Overall())
{
	$output = array();
	$tasks = array();
	$tasks = reviewer_task();	
	$output[] = array("role"=>"reviewer", "tasks"=>$tasks);

	$TPL['tasks'] = $output;		
	template("TASKS");
	$TPL['tasks'] = "";
}
else redirect("logout",false,false,"error=1");	
?>