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
  if (isset($_POST['user_name']) 
  &&  isset($_POST['user_password']) 
  &&  !empty($_POST['user_name']) 
  &&  !empty($_POST['user_password'])) {
    $_SESSION['uname'] = $_POST['user_name'];  
    $_SESSION['password'] = sha1($_POST['user_password']);     
    redirect('main_start.php');
  }
  else {
    $_SESSION['message'] = 'Geben Sie Ihren Benutzernamen (ihre Email-Adresse) '.
                           'und Ihr Passwort an!';
  }
}

$content = new Template(TPLPATH.'login.tpl');
$strContentAssocs = defaultAssocArray();
if (isset($_SESSION['message'])) {
  $strContentAssocs['message'] = $_SESSION['message'];
  unset($_SESSION['message']);
  $strContentAssocs['if'] = array(1);
}
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'startmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(1);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Willkommen bei CoMa!';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;

$strPath = array('CoMa'=>'', 'Login'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>