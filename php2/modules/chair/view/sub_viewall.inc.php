<?
$sql = new SQL();
$sql->connect();
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
else redirect("logout",false,false,"error=1");	
?>