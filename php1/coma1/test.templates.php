<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage Testing
 */
/***/

/**
 * Wichtig damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1',true);

require_once('./include/class.template.inc.php');

$myTemplate = new Template('./templates/test.tpl');

$assocArray = array(
  'tag1' => 'Hallo',
  'tag2' => 'Test',
  'TaG3' => 'Gross Klein test'
);

$myTemplate->assign($assocArray);

$myTemplate->parse();

$myTemplate->output();


?>