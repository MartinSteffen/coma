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

if ((isset($_POST['action']))&&($_POST['action'] == 'login')) {
// Einlog-Versuch
  if (isset($_POST['userMail'])) {
    $_SESSION['uname'] = $_POST['userMail'];
  }
  if (isset($_POST['userPassword'])) {
    $_SESSION['password'] = sha1($_POST['userPassword']);
  }
  redirect('index.php');
}
else {   
// Einlog-Bildschirm
  $mainPage = new Template(TPLPATH.'main.tpl');
  $loginPage = new Template(TPLPATH.'login.tpl');
  
  $strMainAssocs = defaultAssocArray();
  $strMainAssocs['content'] =& $loginPage;
  $strMainAssocs['menue'] = '<a href="{basepath}start.php">Start</a>&nbsp;|&nbsp;&nbsp;';
  $strMainAssocs['submenue'] = '';

  if (isset($_SESSION['message'])) {
    $strMessage = $_SESSION['message'];
    unset($_SESSION['message']);
  }
  else {
    $strMessage = 'Bitte melden Sie sich an!';
  }
  
  $strLoginAssocs = defaultAssocArray();
  $strLoginAssocs['message'] = $strMessage;
  
  
  $mainPage->assign($strMainAssocs);
  $loginPage->assign($strLoginAssocs);
  
  $mainPage->parse();
  $mainPage->output();
}

?>
