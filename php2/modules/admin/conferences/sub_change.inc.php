<? 
$sql = new SQL();
$sql->connect();

if(isAdmin_Overall()){
	$errors=array();
	if (isset($_POST['back']))
		redirect("admin","conferences");

	if ($_POST['submit']) {
		if(!isset($confname)){
			echo("Conference Name not set but required. Please hit the 'Back' Button in your Browser an fill in the information");
			exit();
		}
		if(!isset($cid)){
			echo("Internal Error: Admin Conferences Change: cid not set but required. exiting...");
			exit();
		}
		$cid=$_POST['cid'];
		if(!preg_match("/^\d*$/", $confmin_reviews)){
			echo("Min reviews per paper is no Number. Please hit the 'Back' Button in your Browser and correct the filled in information");
			exit();
		}
		if (count($errors)==0) {
			// write to database and redirect

			$confname=mysql_escape_string($confname);
			$confdescription=mysql_escape_string($confdescription);

			$insertstatement="UPDATE conference SET name='$confname', homepage='$confhomepage', description='$confdescription', abstract_submission_deadline='$confabstract_dl',	paper_submission_deadline='$confpaper_dl', review_deadline='$confreview_dl' , final_version_deadline='$conffinal_dl', notification='$confnotification', conference_start='$confstart', conference_end='$confend', min_reviews_per_paper='$confmin_reviews' WHERE id='$cid'";
			$result=$sql->insert($insertstatement);
		}
	}
	if(!is_Array($result)){
		$message=urlencode("Daten erfolgreich in die Datenbank eingetragen");
	}else{
		$message=urlencode("Daten konnten nicht in die Datenbank geschrieben werden:".$result['text']);		
	}
		redirect("admin","conferences","change_form","cid=$cid&message=$message");
}else{ 
	redirect("logout",false,false,"error=1");	
}
?>
