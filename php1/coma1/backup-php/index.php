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
  redirect('start.php');
}
else {
  $mainPage = new Template(TPLPATH.'main.tpl');
  $menue = new Template(TPLPATH.'nav_index.tpl');
  $loginPage = new Template(TPLPATH.'login.tpl');
  $emptyPage = new Template(TPLPATH.'empty.tpl');

  // SID und basepath in Links einf�gen
  $links = defaultAssocArray();

  $strMainAssocs = defaultAssocArray();
  $strMainAssocs['body'] = & $loginPage;

  $strMainAssocs['content'] = '';

  if(isset($_SESSION['message'])) {
    $strMessage = $_SESSION['message'];
    unset($_SESSION['message']);
  }
  else if( isset($_SESSION['uid']) || isset($_SESSION['password'])) {
    $strMessage = 'Sie sind bereits eingeloggt !!! <BR>'
                 .'Bitte <a href="'.$links['basepath'].'logout.php'.$links['SID']
                 .'"> ausloggen </a> oder zur&uuml;ck '.'zur <a  href="'.$links['basepath']
                 .'start.php'.$links['SID'].'"> Startseite</a>';
    $strMainAssocs['body'] = & $emptyPage;
  }
  else {
    $strMessage = '';
    $strMainAssocs['content'] = ' <h2 align="center"> Bitte Einloggen oder Registrieren </h2>';
  }

  $strMainAssocs['titel'] = ' Willkommen bei CoMa - dem Konferenzmanagement-Tool ';
  $strMainAssocs['menue'] =& $menue;
  $strMainAssocs['submenue'] = '';

  $strLoginAssocs = defaultAssocArray();
  $strLoginAssocs['message'] = $strMessage;

  $mainPage->assign($strMainAssocs);
  $menue->assign(defaultAssocArray());
  $menue->assign($strMenueAssocs);

  $loginPage->assign($strLoginAssocs);
  $emptyPage->assign($strLoginAssocs);

  $mainPage->parse();
  $mainPage->output();
}
?>
