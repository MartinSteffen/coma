<? 
/* This loads the data from DB to prefill Form of editing Functions */

$sql = new SQL();
$sql->connect();
if(isAdmin_Overall())
{

	if(isset($_REQUEST['cid'])){
		$cid = $_REQUEST['cid'];
	}else{
		echo("Error: Admin/Conferences/change_form: Argument cid not set but neccessary... Exiting");
		exit();
	}

	$result = $sql->queryAssoc("SELECT * FROM conference WHERE id = '$cid'");
	$TPL = $result[0];
	$TPL['cid'] = $cid;
	if(isset($_REQUEST['message'])){
		$TPL['message'] = $_REQUEST['message'];
	}
	template("ADMIN_conferences_change");
}else{
	echo("Not proper right to access Module: Logging out...");
	session_destroy();
	redirect("index.php");
}
	?>
