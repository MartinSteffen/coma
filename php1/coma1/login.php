<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1',true);

require_once('./include/header.inc.php');

$mainPage = new Template(TPLPATH.'main.tpl');
$loginPage = new Template(TPLPATH.'login.tpl');

$strAssocs = array();
$strAssocs['path'] = TPLURL;
$strAssocs['content'] =& $loginPage;
$strAssocs['SID'] = $mySession->getUrlId();

$mainPage->assign($strAssoc);

$mainPage->parse();

$mainPage->output();


?>
