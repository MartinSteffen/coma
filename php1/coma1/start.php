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
$menue = new Template(TPLPATH.'nav_start.tpl');

$strMainAssocs = defaultAssocArray();
$strMainAssocs['titel'] = ' Willkommen bei CoMa - dem Konferenzmanagement-Tool ';
$strMainAssocs['content'] = '';
$strMainAssocs['body'] = '';
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] = '';
$strMenueAssocs['loginName'] = $_SESSION['uname'];
$mainPage->assign($strMainAssocs);

$menue->assign(defaultAssocArray());

$mainPage->parse();
$mainPage->output();


?>
