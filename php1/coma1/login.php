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

require_once('./include/header.inc.php');
require_once('./include/class.template.inc.php');

$mainPage = new Template('./templates/sandro/main.tpl');
$loginPage = new Template('./templates/sandro/login.tpl');

$strAssocs = array();
$strAssoc['path'] = './templates/sandro/';
$strAssoc['content'] =& $loginPage;
$strAssoc['SID'] = defined('SID') ? '?'.SID : '';

$mainPage->assign($strAssoc);

$mainPage->parse();

$mainPage->output();


?>
