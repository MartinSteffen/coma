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

$strMainAssocs = defaultAssocArray();
$strMainAssocs['content'] =& $loginPage;

if (isset($_SESSION['message'])) {
  $strMessage = $_SESSION['message'];
  unset($_SESSION['message']);
}
else {
  $strMessage = 'Bitte Einloggen:';
}

$strLoginAssocs = defaultAssocArray();
$strLoginAssocs['message'] = $strMessage;


$mainPage->assign($strMainAssocs);
$loginPage->assign($strLoginAssocs);

$mainPage->parse();
$mainPage->output();


?>
