<?php
include("includes/config.inc.php");

include("includes/definitions.inc.php");
include("includes/login.inc.php");
include("includes/tools.inc.php");
include("includes/templates.inc.php");
include("includes/rights.inc.php");
session_start();


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
			exit("The called subaction '$call_subaction' does not exists!");
		}
	}else{
		exit("The called action '$call_action' does not exists!");
	}
}else{
	exit("The called module '$call_module' does not exists!");
}
?>

