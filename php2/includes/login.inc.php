<?

function loginas($role){
	$_SESSION['role'] = $role;
	$_SESSION['user'] = "Gast";
	$_SESSION['id'] = "000000";
	$_SESSION['name'] = "Gast";
	}
	
function check_login(){
	// {{{
	global $config;
	$eingeloggt = false;

	//checklogin wird noch nicht gebraucht
	if (false){
	if (isset($_SESSION['eingeloggt']) && !isset($_GET['logout'])){
		$eingeloggt = $_SESSION['eingeloggt'];
	}elseif(isset($_GET['logout'])){
		$eingeloggt = false;
		$_SESSION['user'] = "Gast";
		$_SESSION['id'] = "000000";
		$_SESSION['name'] = "Gast";
		$_SESSION['timeout'] = false;
		$_SESSION['message'] = null;
		session_destroy();
		redirect();
	}elseif (isset($_SESSION['timeout'])){
		$eingeloggt = false;
	}else{
		$eingeloggt = false;
		$_SESSION['user'] = "Gast";
		$_SESSION['pnr'] = "000000";
		$_SESSION['name'] = "Gast";
	}
	if ($eingeloggt && $_SESSION['tolo'] < time()){
		$eingeloggt = false;
		$_SESSION['eingeloggt'] = false;
		$_SESSION['timeout'] = true;
		$_SESSION['message'] = "Sie haben die Seite längere Zeit nicht verwendet. <b>Aus Sicherheitsgründen müssen Sie sich daher erneut einloggen.</b> Um sich mit unter einer anderen Windowskennung anzumelden, müssen Sie sich zunächst <a href=\"index.php?logout=1\">ausloggen</a>.";
	}
	if ($eingeloggt){
		if ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'] || $_SESSION['browser'] != $_SERVER['HTTP_USER_AGENT']){
			$eingeloggt = false;
			$_SESSION['eingeloggt'] = false;
		}
	}
	if ($eingeloggt)	{
		$_SESSION['tolo'] = time() + $config['timeout'];
	}
	return $eingeloggt;
	}else{
		return true;
	}
	// }}}
}



