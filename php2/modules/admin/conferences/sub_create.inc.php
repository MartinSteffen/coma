<? 

$sql = new SQL();
$sql->connect();
if(isAdmin_Overall())
{

$errors=array();

if ($_POST['submit']) {
	echo("confname:".$confname);
	if(!isset($confname)){
		echo("Conference Name not set but required. Please hit the 'Back' Button in your Browser an fill in the information");
		exit();
	}
	if(!preg_match("/^\d*$/", $confmin_reviews)){
		echo("Min reviews per paper is no Number. Please hit the 'Back' Button in your Browser and correct the filled in information");
		exit();
	}
	if (count($errors)==0) {
		// write to database and redirect

		$userquery="
			SELECT id FROM person WHERE email LIKE '$confchair_email'";
		$userId=$sql->query($userquery) ;
		$userId=$userId[0]['id'];

		if ($userId) {

			$TPL=$_POST;
			template("ADMIN_conferencesCreateChair");
			die();
		}
		// no user exists -> create new
		$insertstatement="
			INSERT INTO conference (
				name, homepage, description, abstract_submission_deadline,
				paper_submission_deadline, review_deadline, final_version_deadline,
				notification, conference_start, conference_end, min_reviews_per_paper)
			VALUES (
				'$confname','$confhomepage','$confdescription','$confabstract_dl',
				'$confpaper_dl','$confreview_dl','$conffinal_dl',
				'$confnotification','$confstart','$confend','$confmin_reviews')";
		$insertstatement2="
			INSERT INTO person (
				last_name, email, password)
			VALUES (
				'$confchair_lastname','$confchair_email', MD5('$confchair_passwd'))";				

		$dbok=$sql->insert($insertstatement);
		if (is_array($dbok)) {
			$errors[]=$dbok['text'];
		} else {
			$conf_id=$sql->insert_id();
			$dbok=$sql->insert($insertstatement2);
			if (is_array($dbok)) {
				$errors[]=$dbok['text'];
			} else {
				$pers_id=$sql->insert_id();
				$insertstatement3="
					INSERT INTO role (
						conference_id, person_id, role_type, state)
					VALUES (
						$conf_id, $pers_id, 2,1)";
				$dbok=$sql->insert($insertstatement3);
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

	$TPL=$_POST;
	$TPL['ADMIN_conferenceCreate'] = $errors;		
	template("ADMIN_conferenceCreate");
}
else redirect("logout",false,false,"error=1");	
?>
