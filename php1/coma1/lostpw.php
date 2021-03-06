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
$strContentAssocs['message'] = '';

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
    if (sendMail($uid, 'Get a new Password', $mail->getOutput())) {
      $strContentAssocs['message'] = 'An Email with further instructions has been sent to you!';
      $strContentAssocs['if'] = array(2);
    }
    else {
      $strContentAssocs['message'] = 'Failed to send Email!';
      $strContentAssocs['if'] = array(1);
    }
  }
  else {
    if ($myDBAccess->failed()) {
      error('Check Email failed.',$myDBAccess->getLastError());
    }
    $strContentAssocs['message'] = 'Please enter correct Username (Email)';
    $strContentAssocs['if'] = array(1);
  }
}
elseif (isset($_GET['id']) && isset($_GET['key'])) {
  // Aendern des PW
  if ($myDBAccess->getKeyOfPerson($_GET['id']) == $_GET['key']) {
    // Key korrekt
    $objPerson = $myDBAccess->getPerson($_GET['id']);
    if ($myDBAccess->failed()) {
      error('Error retrieving person data', $myDBAccess->getLastError());
    }
    $newPass = generatePassword();
    $myDBAccess->updatePersonPassword($_GET['id'], $newPass);
    if ($myDBAccess->failed()) {
      error('Error updateing persones password', $myDBAccess->getLastError());
    }
    $mail = new Template(TPLPATH.'mail_lostpw2.tpl');
    $strMailAssocs = defaultAssocArray();
    $strMailAssocs['name'] = $objPerson->getName(2);
    $strMailAssocs['email'] = $objPerson->strEmail;
    $strMailAssocs['password'] = $newPass;
    $mail->assign($strMailAssocs);
    $mail->parse();
    if (sendMail($_GET['id'], 'New Password', $mail->getOutput())) {
      $_SESSION['message'] = 'An Email with your new Password has been sent to you!';
      redirect('index.php');
    }
    else {
      $strContentAssocs['message'] = 'Failed to send Email!';
      $strContentAssocs['if'] = array(1);
    }
  }
  else {
    if ($myDBAccess->failed()) {
      error('Check for Userdata failed.',$myDBAccess->getLastError());
    }
    error('Hacking Atempt', 'Hacking Attempt on Accountnumber '.encodeText($_GET['id']));
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