<?php

define('IN_COMA1',true);
require_once('./include/class.template.inc.php');

$myTemplate = new Template();

$myTemplate->readTemplate('./templates/test.tpl');
echo $myTemplate->parse(array());

?>