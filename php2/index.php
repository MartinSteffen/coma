<?php

	error_reporting(E_ERROR | E_WARNING | E_PARSE);

include("includes/config.inc.php");
 if(is_dir("install/")){
	if(COMA_INSTALLED){
		echo("You alreade have installed CoMa onto your System.<br>Please delete the folder '/install' in your CoMa-Directory to have CoMa run properly onto your System.");
		exit();
	}else{
		$uri = $_SERVER['REQUEST_URI'];
		if(preg_match("/\/$/", $uri)){
			header("Location: http://{$_SERVER['HTTP_HOST']}".$uri."install/install.php");
		}else{
			header("Location: http://{$_SERVER['HTTP_HOST']}".dirname($uri)."/install/install.php");
		}
	}
} 
foreach($_REQUEST as $key => $value) {
	$$key=$value;
}
include("includes/definitions.inc.php");
include("includes/sql.class.php");
include("includes/login.inc.php");
include("includes/tools.inc.php");
include("includes/templates.inc.php");
include("includes/rights.inc.php");
include("includes/tasks.inc.php");
include("includes/ptra.inc.php");


session_start(session_id());

if (isset($_REQUEST['Role'])){
	loginas($_REQUEST['Role']);
}

if (is_readable("modules/config.inc.php")){
	require_once("modules/config.inc.php");
}


if (isset($_REQUEST['m'])){
	$call_module = $_REQUEST['m'];
}elseif (isset($config['defaultmodule'])){
	$call_module = $config['defaultmodule'];
	unset($_REQUEST['a']);
}else{
	exit("No default module defined!");
}

if (is_dir("modules/{$call_module}/")){
	if (is_readable("modules/{$call_module}/config.inc.php"))	{
		require_once("modules/{$call_module}/config.inc.php");
	}
	if (isset($_REQUEST['a']))	{
		$call_action = $_REQUEST['a'];
	}	elseif (isset($config['defaultaction'])){
		$call_action = $config['defaultaction'];
		unset($_REQUEST['s']);
	}else{
		exit("No default action defined!");
	}

	if (is_dir("modules/{$call_module}/{$call_action}/")){
		if (is_readable("modules/{$call_module}/{$call_action}/config.inc.php")){
			require_once("modules/{$call_module}/{$call_action}/config.inc.php");
		}
		if (isset($_REQUEST['s'])){
			$call_subaction = $_REQUEST['s'];
		}elseif (isset($config['defaultsubaction'])){
			$call_subaction = $config['defaultsubaction'];
		}else{
			exit("No default subaction defined!");
		}

		if (is_readable("modules/$call_module/$call_action/sub_$call_subaction.inc.php"))		{
			$erlaubt = false;
			/* wenn alle erlaubten Module im Array $rights stehen
			foreach ($rights as $right)
			{
				if ($right["module"] == $call_module && $right["action"] == $call_action)
				{
					$erlaubt = true;
				}
			}
			*/
			$erlaubt = true; //Das muss dann auch weg
			if ($erlaubt){
				require_once("modules/$call_module/$call_action/sub_$call_subaction.inc.php");
			}else{
				require("templates/access_denied.tpl.php");
			}
		}else{
			exit("The called subaction '$call_subaction' does not exist!");
		}
	}else{
		exit("The called action '$call_action' does not exist!");
	}
}else{
	exit("The called module '$call_module' does not exist!");
}

?>

