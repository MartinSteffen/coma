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
require_once('./include/class.dbaccess.inc.php');
require_once('./include/session.inc.php');

$myTemplate = new Template('./templates/login.tpl');

$assocArray = array(
  'tag1' => 'Hallo',
  'tag2' => 'Test'
);

$myTemplate->assign($assocArray);

$myTemplate->parse();

$myTemplate->output();


?>