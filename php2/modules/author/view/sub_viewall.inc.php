<?  //Do not change please
$sql = new SQL();
$sql->connect();
if(isAuthor_Overall())
{
	$output = array();
	$tasks = array();
	$tasks = author_task();	
	$output[] = array("role"=>"author", "tasks"=>$tasks);

	$TPL['tasks'] = $output;		
	template("TASKS");
	$TPL['tasks'] = "";
}
else redirect("logout",false,false,"error=1");	
?>