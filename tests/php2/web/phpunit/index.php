<?php

// Required Libraries
require_once 'PHPUnit.php';
require_once 'PHPUnit/gui/HTML.php';

// Required Test Classes
require_once 'testsuites/RequiredFunctions.php';
//require_once 'testsuites/dbAccess.php';

// Build the Suites
$suites = array();

$suites[] = new PHPUnit_TestSuite("RequiredFunctions");
//$suites[] = new PHPUnit_TestSuite("Rtpa");
//$suites[] = new PHPUnit_TestSuite("ComaExample");
//$suites[] = new PHPUnit_TestSuite("dbAccess");

// Build the GUI and Run the Suites
$gui = new PHPUnit_GUI_HTML($suites);
//$gui->addSuites($suites);
$gui->show();

?>
