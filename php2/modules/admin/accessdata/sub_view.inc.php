<? 

$sql = new SQL();
$sql->connect();

if(isAdmin_Overall())
{

$errors=array();

if ($_POST['submit']) {
	// TODO: check form data	

	if (count($errors)==0) {

		// check access data

		$myHandle=@mysql_connect($dbdata_host, $dbdata_user, $dbdata_pass) or $errors[]="Could not connect to given database host with given user and pass!";
		@mysql_select_db($dbdata_name, $myHandle) or $errors[]="Could not select database instance named $dbdata_name!";
		@mysql_close($myHandle);

		$ftphandle=ftp_connect($ftp_host) or $errors[]="Could not connect to given FTP host!";
		if ($ftphandle) {
			@ftp_login($ftphandle, $ftp_user, $ftp_pass) or $errors[]="Could not login to FTP host with given username and password!";
			if ($ftphandle && $ftp_dir) {
				@ftp_chdir($ftphandle, $ftp_dir) or $errors[]="Could not change directory to '$ftp_dir' on FTP-Server!";
			}
		}
	}
	if (count($errors)==0) {	

		// generate configAsString

		$configAsString="<?PHP\n"
			."// This is an automatically generated configuration file for CoMa!!\n"
			."// Do not change this file manually unless you exactly know what you are doing!\n\n"
			."define('COMA_INSTALLED',true);\n"
			."\$dbhost='$dbdata_host'; \n"
			."\$dbuser='$dbdata_user'; \n"
			."\$dbpass='$dbdata_pass'; \n"
			."\$dbname='$dbdata_name'; \n\n"
			."\$ftphost='$ftp_host'; \n"
			."\$ftpuser='$ftp_user'; \n"
			."\$ftppass='$ftp_pass'; \n"
			."\$ftpdir='$ftp_dir'; \n"
			."\$httpftpurl='$ftp_baseurl'; \n"
			." \n"
			." \n"
			." \n";
	
		// try filesystem write access
		if (is_writable("includes/config.inc.php")) {
			$file=fopen("includes/config.inc.php","w");
			if (!$file) {
				$needManualReplacement=true;
			} else {
				fwrite($file, $configAsString);
				fclose($file);
			}
		} else {
			$needManualReplacement=true;
		}

		if ($needManualReplacement) {
			$TPL['configAsString']=$configAsString;
			template("ADMIN_accessdataStepTwo");
			die();
		} else {
			$TPL['resultMessage']="New access data successfully checked and saved!";
		}		
	}
}

	$TPL['errors']=$errors;

	include("templates/ADMIN_accessdataView.tpl.php");
}
else redirect("logout",false,false,"error=1");	
?>
