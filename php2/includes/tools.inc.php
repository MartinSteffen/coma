<?
/*Diese Funktion leitet den HTML-Header auf eine ander URL */ 

function redirect($m = false, $a = false, $s = false, $mehr = false){
	// {{{
	if ($m){
		$m = "m=$m";
	}
	if ($a){
		$a = "&a=$a";
	}
	if ($s){
		$s = "&s=$s";
	}
	if ($mehr) 
	{
		$mehr = "&".$mehr;
	} 
  if (headers_sent()){
        print("<a href=\"http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}?{$m}{$a}{$s}{$mehr}\">Zum Fortfahren bitte hier klicken!</a>");
    }else{
      //header("Location: http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}?{$m}{$a}{$s}{$mehr}");
			header("Location: {$_SERVER['PHP_SELF']}?{$m}{$a}{$s}{$mehr}");

    }
	exit();
	// }}}
}


//Makes the encoded password
function makePassword($pass)
{

	return $pass;
}

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
?>
