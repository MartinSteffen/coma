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
$strContentAssocs = defaultAssocArray();

if (isset($_POST['submit'])) {
  /* E-Mail eingeben */
  $uname = $_POST['user_name'];
  if ($uid = $myDBAccess->getPersonIdByEmail($uname)) {
    // gueltiger User
    $objPerson = $myDBAccess->getPerson($uid);
    if ($myDBAccess->failed()) {
      error('Error retrieving person data', $myDBAccess->getLastError());
    }
    $key = $myDBAccess->getKeyOfPerson($uid);
    if ($myDBAccess->failed()) {
      error('Error retrieving person data', $myDBAccess->getLastError());
    }
    $mail = new Template(TPLPATH.'mail_lostpw1.tpl');
    $strMailAssocs = defaultAssocArray();
    $strMailAssocs['name'] = $objPerson->getName(2);
    $strMailAssocs['link'] = COREURL . "lostpw.php?id=$uid&key=$key";
    $mail->assign($strMailAssocs);
    $mail->parse();
    sendMail($uid, 'Get a new Password', $mail->getOutput());
    $strContentAssocs['message'] = '';
    $strContentAssocs['if'] = array(2);
  }
  else {
    if ($myDBAccess->failed()) {
      error('Check e-mail failed.',$myDBAccess->getLastError());
    }
    $strContentAssocs['message'] = 'Please enter correct Username (E-mail)';
    $strContentAssocs['if'] = array(1);
  }
}

$content = new Template(TPLPATH.'lostpw.tpl');
$strContentAssocs['uname'] = encodeText($uname);
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