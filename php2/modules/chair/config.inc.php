<?php
/* DEBUG - delete later!!! */
if (!isset($_SESSION['userID']))
{
	$_SESSION['userID'] = 1;
}
/* END DEBUG */

$_SESSION['role'] = "Chair";
$config["defaultaction"] = "view";
?>
