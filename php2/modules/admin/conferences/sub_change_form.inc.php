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

	if (isset($_REQUEST['submit'])) {

		if (!$_REQUEST['confname']) {
			$errors[ ]="No conference name given!";
		}
		if (!$_REQUEST['confdescription']) {
			$errors[ ]="No conference description given!";
		}
		if (count($errors)==0) {
			$query="UPDATE conference SET name='". $_REQUEST['confname'] . "', description='" . $_REQUEST['confdescription'] . "', homepage='" . $_REQUEST['confhomepage'] . "' "
						." WHERE id=$cid";
			$result=$sql->insert($query);
			if (!$result) {
				$errors[ ] = $sql->error();
			}
		}
		if (count($errors)==0) {
			redirect("admin","conferences",false, false);
		}
	}

	$result = $sql->queryAssoc("SELECT * FROM conference WHERE id = '$cid'");
	$TPL = $result[0];
	$TPL['cid'] = $cid;
	if(isset($_REQUEST['message'])){
		$TPL['message'] = $_REQUEST['message'];
	}
	if (count($errors)>0) {
		$TPL['errors'] = $errors;
	}
	template("ADMIN_conferenceChange");
}else{
	echo("Not proper right to access Module: Logging out...");
	session_destroy();
	redirect("index.php");
}
	?>
