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

$uname = session('uname', false);
$message = '';
if ((isset($_POST['submit'])) {
  /* E-Mail eingeben */
  $uname = $_POST['user_name'];
  if (checkEmail($uname)) {
    // gueltiger User
  }
  else {
    $message = 'Please enter correct Username (E-mail)';
  }
}

$content = new Template(TPLPATH.'lostpw.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = $message;
$strContentAssocs['uname'] = encodeText($uname);
if (!empty($strContentAssocs['message'])) {
  $strContentAssocs['if'] = array(1);
}
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'startmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(); // kein Menu
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Welcome to CoMa!';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = 'CoMa  |  I lost my Password';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>