<?
/*Diese Funktion leitet den HTML-Header auf eine ander URL */ 

function redirect($m = false, $a = false, $s = false, $mehr = false){
	// {{{
	$toShow = 0;
	if ($m)
	{
		if($toShow == 0)
		{
			$m = "?m=$m";			
		}
		else
		{
			$m = "&m=$m";
		}
		$toShow++;
	}
	if ($a)
	{
		if($toShow == 0)
		{
			$a = "?a=$a";
		}
		else
		{
			$a = "&a=$a";
		}
		$toShow++;
	}
	if ($s)
	{
		if($toShow == 0)
		{
			$s = "?s=$s";
		}
		else
		{
			$s = "&s=$s";
		}
		$toShow++;
	}
	if ($mehr) 
	{
		if($toShow == 0)
		{
			$mehr = "?".$mehr;
		}
		else
		{
			$mehr = "&".$mehr;
		}
	} 
  if (headers_sent()){
        print("<a href=\"http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}{$m}{$a}{$s}{$mehr}\">Zum Fortfahren bitte hier klicken!</a>");
    }else{
      //header("Location: http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}{$m}{$a}{$s}{$mehr}");
			header("Location: {$_SERVER['PHP_SELF']}{$m}{$a}{$s}{$mehr}");

    }
	exit();
	// }}}
}


//Makes the encoded password
function makePassword($pass)
{
	$pass = MD5(strtolower($pass));
	return $pass;
}
// {{{
/*
function getRoles($person_id, $filter_conference = null, $filter_role = null){ 
	global $sql;
	if($filter_role != null){
		$result = $sql->query("SELECT conference_id FROM ".SQLROLE." where person_id = '$person_id' AND role_type ='$filter_role'");
		if($result->nextRow("?")){
			if($filter_conference != null){
				while($result->getField['conference_id'] != $filter_conference){
					if($result->nextRow("?")){
						$result->nextRow();
					}else{
						echo("ERROR: In Conference no such Role for specified User");
						exit();
					}
					resetCounter();
				}
				$_SESSION['conference'] = new array(1, $filter_conference);
			}else{
				$a = new array();
				while($result->nextRow()){
					$a[] = $result->getField("conference_id");
				}
				resetCounter();
				$_SESSION['conference'] = $a;
			}
		}
		$_SESSION['role'] = new array(1, $filter_role);
		return $filter_role;
	}else{
		if($filter_conference != null){
			$result = $sql->query("SELECT role_type FROM ".SQLROLE." where person_id = '$person_id' AND conference_id ='$filter_conference'");
				$a = new array();
				while($result->nextRow()){
					$a[] = $result->getField("role_type");
				}
				resetCounter();
				$_SESSION['role'] = $a;
				return $a;
		}else{
			$result = $sql->query("SELECT role_type, conference_id FROM ".SQLROLE." where person_id = '$person_id'");
			
			$a = new array();
			$b = new array();
			while($result->nextRow()){
				$akt = $result->getField("conference_id");
				if(!in_array($akt, $b))
					$b[] = $akt;
				$akt = $result->getField("role_type");
				if(!in_array($akt, $a))
					$a[] = $akt;
			}
			resetCounter();
			$_SESSION['conference'] = $b;
			$_SESSION['role'] = $a;
			return $a;
		}
	}
}	
		
function getModules($roles)
{
 //TODO: hole Modules aus der DB
}

function getRights($userid)
{
	// {{{
	return getModules(getRoles($userid));
	// }}}
}
*/

// }}}
function isDate($date){
	if (!preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $string)){
		return false;
	}else{
		return true;
	}
}

function dump($var){
	var_dump($var);
	exit();
}

function ftp_rmAll($conn_id,$dst_dir)
{ // {{{
  $ar_files = ftp_rawlist($conn_id, $dst_dir) or die("function ftp_rmAll: ftp_rawlist() failed!");
  if (is_array($ar_files)) { // makes sure there are files
   foreach ($ar_files as $st_file) { // for each file
     if (ereg("([-d][rwxst-]+).* ([0-9]) ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9]) ([0-9]{2}:[0-9]{2}) (.+)",$st_file,$regs)) {
       if (substr($regs[1],0,1)=="d") { // check if it is a directory
         ftp_rmAll($conn_id, $dst_dir."/".$regs[8]); // if so, use recursion
       } else {
         ftp_delete($conn_id, $dst_dir."/".$regs[8]) or die("function ftp_rmAll: ftp_delete failed()"); // if not, delete the file
       }
     }
   }
  }
  ftp_rmdir($conn_id, $dst_dir) or die("function ftp_rmAll: ftp_rmdir() failed!"); // delete empty directories
	return true; // }}}
}


function cleanup_ftp($paper_id, $step_id = false, $ftphandle = NULL,  $msg = ""){
	// {{{
	if (!$step_id) {
		$step_id = 6;
		}
	switch($step_id){
		case 6:
			if ($ftphandle == NULL){
				$ftphandle=@ftp_connect($ftphost) or die("function cleanup_ftp: ftp_connect() failed!");
			}
			@ftp_login($ftphandle, $ftpuser, $ftppass) or die("function cleanup_ftp: ftp_login() failed!");
			$msg = "<font style=color:green>Paper deleted succssesfully.</font>";
		case 5:
			if ($msg == ""){
				$msg = "<font style=color:red>Error: (Author_new_save) ftp_put failed! Maybe no permission.</font>";
			}
		case 4:
			if ($msg == ""){
				$msg = "<font style=color:red>Error: (Author_new_save) ftp_chdir() failed! Maybe no permission.</font>";
			}
			ftp_rmAll($ftphandle, "./" . $ftpdir . "/" . $paper_id) or die("function cleanup_ftp: ftp_rmAll failed, but we can't be here anyway, because we are already dead!");
		case 3:
			if ($msg == ""){
				$msg = "<font style=color:red>Error: (Author_new_save) ftp_mkdir() failed! Maybe wrong path.</font>";
			}
		case 2:
			if ($msg == ""){
				$msg = "<font style=color:red>Error: (Author_new_save) ftp_login() failed! Maybe wrong username or password.</font>";
			}
			ftp_quit($ftphandle);
		case 1:
			if ($msg == ""){
				$msg = "<font style=color:red>Error: (Author_new_save) ftp_connect() failed!</font>";
			}
		default:
			$SQL = "DELETE FROM paper WHERE id = ". $paper_id;
			$result = $sql->insert($SQL);
			redirect("author", "view", "papers", "msg=".$msg);
	}	
	// }}}
}


?>
