<?php
/**
 * @version $Id: start.php 803 2004-12-13 22:55:56Z miesling $
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

if ((isset($_POST['action']))&&($_POST['action'] == 'login')) {
// Einlog-Versuch
  if (isset($_POST['userMail'])) {
    $_SESSION['uname'] = $_POST['userMail'];
  }
  if (isset($_POST['userPassword'])) {
    $_SESSION['password'] = sha1($_POST['userPassword']);
  }
  redirect('start.php');
}
else {
$mainPage = new Template(TPLPATH.'main.tpl');
$menue = new Template(TPLPATH.'nav_index_regi.tpl');
$loginPage = new Template(TPLPATH.'register.tpl');

$strMainAssocs = defaultAssocArray();
$strMainAssocs['titel'] = ' Willkommen bei CoMa - dem Konferenzmanagement-Tool ';
$strMainAssocs['content'] = '
<h2 align="center"> Hinweis: Bitte registrieren Sie sich nur einmal ! </h2>'
;
$strMainAssocs['body'] = & $loginPage;
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] = '<a href="index.php" class="menue"> Login </a>';

// $strMenueAssocs['loginName'] = $_SESSION['uname'];
if (isset($_SESSION['message'])) {
  $strMessage = $_SESSION['message'];
  unset($_SESSION['message']);
}
else {
  $strMessage = '';
}

$strLoginAssocs = defaultAssocArray();
$strLoginAssocs['message'] = $strMessage;

$mainPage->assign($strMainAssocs);
$menue->assign(defaultAssocArray());
$menue->assign($strMenueAssocs);

$loginPage->assign($strLoginAssocs);

$mainPage->parse();
$mainPage->output();

 }


?>
