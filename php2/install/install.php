<?php

	error_reporting(E_ERROR | E_WARNING | E_PARSE);

include("templates/header.tpl.php");
include("includes/tools.inc.php");

$errors=array();

foreach($_REQUEST as $key => $value) {
	$$key=$value;
}

if($_REQUEST['submit']) {
	// TODO: check form data	& admin data

	if (count($errors)==0) {

		// check access data

		$myHandle=@mysql_connect($dbdata_host, $dbdata_user, $dbdata_pass) or $errors[]="Could not connect to given database host with given user and pass!";
		@mysql_select_db($dbdata_name, $myHandle) or $errors[]="Could not select database instance named '$dbdata_name'!";
		@mysql_close($myHandle);

		$ftphandle=@ftp_connect($ftp_host) or $errors[]="Could not connect to given FTP host!";
		if ($ftphandle) {
			@ftp_login($ftphandle, $ftp_user, $ftp_passwd) or $errors[]="Could not login to FTP host with given username and password!";
			if ($ftphandle && $ftp_directory) {
				@ftp_chdir($ftphandle, $ftp_directory) or $errors[]="Could not change directory to '$ftp_directory' on FTP-Server!";
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
			."\$ftppass='$ftp_passwd'; \n"
			."\$ftpdir='$ftp_dir'; \n"
			."\$httpftpurl='$ftp_baseurl'; \n"
			." \n"
			." \n"
			." \n";
	
		// try filesystem write access
		if (is_writable("../includes/config.inc.php")) {
			$file=fopen("../includes/config.inc.php","w");
			if (!$file) {
				$needManualReplacement=true;
			} else {
				fwrite($file, $configAsString);
				fclose($file);
			}
		} else {
			$needManualReplacement=true;
		}

		$myHandle=@mysql_connect($dbdata_host, $dbdata_user, $dbdata_pass) or $errors[]="Could not connect to given database host with given user and pass!";
		@mysql_select_db($dbdata_name, $myHandle) or $errors[]="Could not select database instance named '$dbdata_name'!";

		$lines=file("install.sql.php");
		foreach($lines as $line) {
			$ok=mysql_query($line);
			if (!$ok) echo mysql_error();
		}

		$adminquery="INSERT INTO person (id, last_name, email, password) VALUES ( 1, '$admin_lastname', '$admin_email', MD5('$admin_pass'))";
		$adminquery2="INSERT INTO role(conference_id, person_id, role_type, state) VALUES (0,1,1,1)";		

		mysql_query($adminquery);
		mysql_query($adminquery2);

		@mysql_close($myHandle);
		

		if ($needManualReplacement) {
			include("install2.php");
			die();
		} else {
			include("install3.php");
			die();
		}		
	}
}

?>

<table border="0" width="90%">
	<tr>
		<td>
			<fieldset>
<legend>
<font class="textBold" size="+2"><b>CoMa - a conference manager - Installation step 1</b></font>
</legend>
<form action="install.php" method="post">
<table border="0" cellspacing="0" cellpadding="5" bordercolor="#000000" align="center">
<?php 

if ($errors) {
	echo "<TR><TD class='textBold' colspan='2'><fieldset><div style='color:red;'><legend>Errors</legend></div><ul>";
	foreach($errors as $errortext) {
		echo "<LI style='color:red;'>".$errortext."</LI>";
	}
	echo "</UL></fieldset></td></tr>";
}
?>
<tr>
<td>
<fieldset>
<legend>
<font class="textBold" size="+2"><b>Database Access Data</b></font>
</legend>
<table border="0" width="100%">
<tr>
<td class="textBold" >
Database host:
</td>
<td align="right">
<input class="text" type="text" name="dbdata_host" size="40" maxlength="80" value="<?php echo ($_POST['dbdata_host']?$_POST['dbdata_host'] : "localhost") ?>">
</td>
</tr>
<tr>
<td class="textBold" >
Database user:
</td>
<td align="right">
<input class="text" type="text" name="dbdata_user" size="40" maxlength="80" value="<?php echo ($_POST['dbdata_user']?$_POST['dbdata_user'] : "") ?>">
</td>
</tr>
<tr>
<td class="textBold" >
Database password:
</td>
<td align="right">
<input class="text" type="password" name="dbdata_pass" size="40" maxlength="80" value="">
</td>
</tr>
<tr>
<td class="textBold" >
Database instance name:
</td>
<td align="right">
<input class="text" type="text" name="dbdata_name" size="40" maxlength="80" value="<?php echo ($_POST['dbdata_name']?$_POST['dbdata_name'] : "") ?>">
</td>
</tr>
</table>
</fieldset>
<br>
<fieldset>
<legend>
<font class="textBold" size="+2"><b>FTP-Webspace Access Data</b></font>
</legend>
<table>
<tr>
<td class="textBold" >
FTP-host:
</td>
<td>
<input class="text" type="text" name="ftp_host" size="40" maxlength="80" value="<?php echo ($_POST['ftp_host']?$_POST['ftp_host']:"www.myftpserver.net") ?>">
</td>
</tr>
<tr>
<td class="textBold" >
FTP-user:
</td>
<td>
<input class="text" type="text" name="ftp_user" size="40" maxlength="80" value="<?php echo ($_POST['ftp_user']?$_POST['ftp_user']:"") ?>">
</td>
</tr>
<tr>
<td class="textBold" >
FTP-password:
</td>
<td>
<input class="text" type="password" name="ftp_passwd" size="40" maxlength="80" value="">
</td>
</tr>
<tr>
<td class="textBold" >
FTP-directory:
</td>
<td>
<input class="text" type="text" name="ftp_directory" size="40" maxlength="80" value="<?php echo ($_POST['ftp_directory']?$_POST['ftp_directory']:"papers") ?>">
</td>
</tr>
<tr>
<td class="textBold" >
HTTP base URL to FTP-webspace (including trailing "/"):
</td>
<td>
<input class="text" type="text" name="ftp_baseurl" size="40" maxlength="80" value="<?php echo ($_POST['ftp_baseurl']?$_POST['ftp_baseurl']:"http://www.myftpserver.net/papers/") ?>">
</td>
</tr>
</table>
</fieldset>
<br>
<fieldset>
<legend>
<font class="textBold" size="+2"><b>Administrator account data</b></font>
</legend>
<table border="0" width="100%">
<tr>
<td class="textBold" >
Administrator lastname:
</td>
<td align="right">
<input class="text" type="text" name="admin_lastname" size="40" maxlength="80" value="<?php echo ($_POST['admin_lastname']?$_POST['admin_lastname']:"") ?>">
</td>
</tr>
<tr>
<td class="textBold" >
Administrator email:
</td>
<td align="right">
<input class="text" type="text" name="admin_email" size="40" maxlength="80" value="<?php echo ($_POST['admin_email']?$_POST['admin_email']:"") ?>">
</td>
</tr>
<tr>
<td class="textBold" >
Administrator password:
</td>
<td align="right">
<input class="text" type="password" name="admin_pass" size="40" maxlength="80" value="">
</td>
</tr>
<tr>
<td class="textBold" >
Administrator password (confirm):
</td>
<td align="right">
<input class="text" type="password" name="admin_pass_confirm" size="40" maxlength="80" value="">
</td>
</tr>
</table>
</fieldset>
</td>
</tr>
<tr>
<td align=center colspan="2">
<input class="text" type="submit" value="submit" name="submit">&nbsp;&nbsp;&nbsp;<input type="reset" value="reset">
</td>
</tr>
</table>
</form>
</fieldset>
</td>
</tr>
</table>

<?php

include("templates/footer.tpl.php");

?>
