<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
require_once('./include/header.inc.php');

if ((isset($_POST['action']))&&($_POST['action'] == 'login')) {
  /* Einlog-Versuch */
  if (isset($_POST['user_name']) && isset($_POST['user_password']) &&
      !empty($_POST['user_name']) && !empty($_POST['user_password'])) {
    $_SESSION['uname'] = $_POST['user_name'];  
    $_SESSION['password'] = sha1($_POST['user_password']);     
    if ($myDBAccess->checkLogin()) {      
      redirect('main_start.php');
    }
    else {
      unset($_SESSION['uname']);
      unset($_SESSION['password']);
      $_SESSION['message'] = 'Falscher Benutzername oder Passwort!';
    }
  }
  else {
    $_SESSION['message'] = 'Geben Sie Ihren Benutzernamen (ihre Email-Adresse) '.
                           'und Ihr Passwort an!';
  }
}

$content = new Template(TPLPATH.'login.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = '';

if (isset($_SESSION['message'])) {
  $strContentAssocs['message'] = '<p class="message">'.$_SESSION['message'].'</p>';
  unset($_SESSION['message']);
}
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'startmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['menu'] = array(1, 2, 3, 4);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Willkommen bei CoMa!';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;

$strPath = array('CoMa'=>'', 'Login'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);
$strMainAssocs['date'] = date("l, d.m.Y");

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>