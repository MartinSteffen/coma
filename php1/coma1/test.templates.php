<?php
/**
 * @version $Id$
 * @package test1
 */

/**
 * Wichtig damit Coma1 Datein eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1',true);

require_once('./include/class.template.inc.php');

$myTemplate = new Template('./templates/test.tpl');

$assocArray = array(
  'tag1' => 'Hallo',
  'tag2' => 'Test'
);

$myTemplate->assign($assocArray);

echo $myTemplate->parse();


?>