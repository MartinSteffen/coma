<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
/**
 * User muss auf der Seite nicht eingeloggt sein
 *
 * @ignore
 */
define('NEED_NO_LOGIN', true);
require_once('./include/header.inc.php');

if (checkLogin()) {
  redirect('index.php');
}
elseif (isset($_SESSION['uname']) && isset($_SESSION['password'])) {
  session_delete('password');
  $_SESSION['message'] = 'Username or Password is wrong!';
}

if ((isset($_POST['action'])) && ($_POST['action'] == 'login')) {
  /* Einlog-Versuch */
  if (isset($_POST['user_name'])
  &&  isset($_POST['user_password'])
  &&  !empty($_POST['user_name'])
  &&  !empty($_POST['user_password'])) {
    $_SESSION['uname'] = $_POST['user_name'];
    $_SESSION['password'] = sha1($_POST['user_password']);
    redirect('index.php');
  }
  else {
    $_SESSION['message'] = 'Please enter your Username (Email) and your Password.';
  }
}

$content = new Template(TPLPATH.'login.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = session('message', false);
session_delete('message');
if (!empty($strContentAssocs['message'])) {
  $strContentAssocs['if'] = array(1);
}
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'startmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(1);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Welcome to CoMa!';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = 'CoMa  |  Login';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>