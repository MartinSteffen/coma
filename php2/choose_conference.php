<?
include 'security.php';
if(isset($_POST['Submit']))
{
	if(isset($_POST['conf_id']))
	{
		$_SESSION['conf_id']=$_POST['conf_id'];
		header("Location: index.php");
		exit;
	}
}

include 'templates/header.php';
include 'templates/upper-links.php';
include 'templates/conferences-header.php';

//GET THE CONFERENCES FROM THE DATABASE

//---------------------------------------------
//DEBUG TEST
//---------------------------------------------

$conf_id=0;
$conf_abreviation="XML";
$conf_name="The syntax of XML";
include 'templates/conferences-main.php';

$conf_id=1;
$conf_abreviation="Databases";
$conf_name="Sybase database forum";
include 'templates/conferences-main.php';

$conf_id=2;
$conf_abreviation="Bla";
$conf_name="Bla bli blo";
include 'templates/conferences-main.php';


//---------------------------------------------
//END DEBUG TEST
//---------------------------------------------

include 'templates/conferences-footer.php';
include 'templates/footer.php';
?>