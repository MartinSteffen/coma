<? 

$sql = new SQL();
$sql->connect();
if(isAdmin_Overall())
{
			$TPL=unserialize(urldecode($_POST['TPLdata']));

$errors=array();

if ($_POST['submit']) {
	//if(!isset($TPL['confname'])){
	//	echo("Conference Name not set but required. Please hit the 'Back' Button in your Browser an fill in the information");
	//	exit();
	//}
	if(!preg_match("/^\d*$/", $TPL['confmin_reviews'])){
		echo("Min reviews per paper is no Number. Please hit the 'Back' Button in your Browser and correct the filled in information");
		exit();
	}
	if (count($errors)==0) {
		// write to database and redirect

		if (isset($_REQUEST['createNewChair']) && $_REQUEST['createNewChair']=="yes") {
			var_dump("createNewChair==true");
			template("ADMIN_conferenceCreate");
			die();
		}

		$userquery="
			SELECT id FROM person WHERE email LIKE '{$TPL['confchair_email']}'";
		$userId=$sql->query($userquery) ;
		$userId=$userId[0]['id'];

		if ($userId) {


			$insertstatement="
				INSERT INTO conference (
					name, homepage, description, abstract_submission_deadline,
					paper_submission_deadline, review_deadline, final_version_deadline,
					notification, conference_start, conference_end, min_reviews_per_paper)
				VALUES (
					'{$TPL['confname']}','{$TPL['confhomepage']}','{$TPL['confdescription']}','1970-01-01',
					'1970-01-01','1970-01-01','1970-01-01',
					'1970-01-01','1970-01-01','1970-01-01','3')";
			$dbok=$sql->insert($insertstatement);
			if (is_array($dbok)) {
				$errors[]=$dbok['text'];
			} else {
				$conf_id=$sql->insert_id();
				$insertstatement2="
					INSERT INTO role (
						conference_id, person_id, role_type, state)
					VALUES (
						'$conf_id', '$userId', 2,1)";
				$dbok=$sql->insert($insertstatement2);
				if (is_array($dbok)) {
					$errors[]=$dbok['text'];
				} else {
						redirect("admin",false,false,"a=conferences");
				}
			}

		}
 	}
}

//TODO:
//process inputs

	$TPL['ADMIN_conferenceCreate'] = $errors;		
	template("ADMIN_conferenceCreate");
}
else redirect("logout",false,false,"error=1");	
?>
