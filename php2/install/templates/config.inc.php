<?php

header("Content-type: text/plain");

if (isset($_REQUEST['text'])) {
	echo stripslashes(urldecode($_REQUEST['text']));
} else {
	exit("No message specified!");
}

?>