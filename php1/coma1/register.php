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

$content = new Template(TPLPATH.'register.tpl');
$strContentAssocs = defaultAssocArray();

// Teste, ob Daten mit der Anfrage des Benutzer mitgeliefert wurde.
if (isset($_POST['email'])){

  // Anlegen der Person in der Datenbank
  $strContentAssocs['first_name']  = $_POST['first_name'];
  $strContentAssocs['last_name']   = $_POST['last_name'];
  $strContentAssocs['email']       = $_POST['email'];
  $strContentAssocs['name_title']  = $_POST['name_title'];
  $strContentAssocs['affiliation'] = $_POST['affiliation'];
  $strContentAssocs['street']      = $_POST['street'];
  $strContentAssocs['postalcode']  = $_POST['postalcode'];
  $strContentAssocs['city']        = $_POST['city'];
  $strContentAssocs['state']       = $_POST['state'];
  $strContentAssocs['country']     = $_POST['country'];
  $strContentAssocs['phone']       = $_POST['phone'];
  $strContentAssocs['fax']         = $_POST['fax'];

  // Teste, ob alle Pflichtfelder ausgefuellt wurden
  if (empty($_POST['last_name'])
  ||  empty($_POST['email'])
  ||  empty($_POST['user_password'])
  ||  empty($_POST['password_repeat'])) {
    $strMessage = 'You have to fill in the fields <b>Last name</b>, <b>E-mail</b>, and <b>Password</b>!';
  }
  // Teste, ob Passwort mit der Wiederholung uebereinstimmt
  else if ($_POST['user_password'] != $_POST['password_repeat']) {
    $strMessage = 'Your password confirmation is not the same as your password!';
  }
  // Teste, ob die Email gueltig ist
  else if (!ereg("^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$", $_POST['email'])) {
    $strMessage = 'Please enter a valid E-mail address!';
  }
  // Teste, ob die Email bereits vorhanden ist
  else if ($myDBAccess->checkEmail($_POST['email'])) {
    $strMessage = 'Account with the given E-mail address is already existing! '.
                  'Please use that account or enter another E-mail address!';
  }
  // Versuche einzutragen
  else {
    $result = $myDBAccess->addPerson($_POST['first_name'],
                                     $_POST['last_name'],
                                     $_POST['email'],
                                     $_POST['name_title'],
                                     $_POST['affiliation'],
                                     $_POST['street'],
                                     $_POST['city'],
                                     $_POST['postalcode'],
                                     $_POST['state'],
                                     $_POST['country'],
                                     $_POST['phone'],
                                     $_POST['fax'],
                                     $_POST['user_password']);
    if (!empty($result)) {
      // Erfolg (also anderes Template)
      $content = new Template(TPLPATH.'confirm_register.tpl');
    }
    else if ($myDBAccess->failed()) {
      // Datenbankfehler?
      $strMessage = 'An error occured during your registration:<br>'
                   .$myDBAccess->getLastError()
                   .'<br>Please try again to register!';
    }
  }
}

$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $strContentAssocs['if'] = array(1);
}

$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'startmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(2);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Register new User';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = 'CoMa  |  Register';

$main->assign($strMainAssocs);
$main->parse();
$main->output();
?>