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
require_once('./include/header.inc.php');

// Pruefe Zugriffsberechtigung auf die Seite
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');
}

$content = new Template(TPLPATH.'edit_profile.tpl');
$strContentAssocs = defaultAssocArray();

// Lade die Daten der Person
$objPerson = $myDBAccess->getPersonDetailed(session('uid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving actual person.', $myDBAccess->getLastError());
}

// Teste, ob Daten mit der Anfrage des Benutzers mitgeliefert wurde.
if ((isset($_POST['action']))&&($_POST['action'] == 'update')) {
  // Teste, ob alle Pflichtfelder ausgefuellt wurden
  if (empty($_POST['last_name'])
  ||  empty($_POST['email'])) {
    $strMessage = 'You have to fill in the fields <b>Last name</b>, and <b>Email</b>!';
  }
  // Teste, ob die Email gueltig ist
  elseif ((!preg_match("/^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$/", $_POST['email']))
        &&(!preg_match("/^([a-zA-Z0-9\.\_\-]+)@(([0-9]|1?\d\d|2[0-4]\d|25[0-5])\.){3}([0-9]|1?\d\d|2[0-4]\d|25[0-5])$/",  $_POST['email']))) {
    $strMessage = 'Please enter a valid Email address!';
  }
  // Teste, ob die Email bereits vorhanden ist
  elseif ($_POST['email'] != $objPerson->strEmail &&
           $myDBAccess->checkEmail($_POST['email'])) {
    if ($myDBAccess->failed()) {
      error('Check Email failed.', $myDBAccess->getLastError());
    }
    $strMessage = 'Account with the given Email address already exists! '.
                  'Please use another Email address!';
  }
  elseif ($_POST['password1'] != $_POST['password2']) {
    $strMessage = 'You have to enter your new Password correctly twice!';
  }
  else {
    $objPerson->strFirstName   = $_POST['first_name'];
    $objPerson->strLastName    = $_POST['last_name'];
    $objPerson->strEmail       = $_POST['email'];
    $objPerson->strAffiliation = $_POST['affiliation'];
    $objPerson->strTitle       = $_POST['name_title'];
    $objPerson->strStreet      = $_POST['street'];
    $objPerson->strCity        = $_POST['city'];
    $objPerson->strPostalCode  = $_POST['postalcode'];
    $objPerson->strState       = $_POST['state'];
    $objPerson->strCountry     = $_POST['country'];
    $objPerson->strPhone       = $_POST['phone'];
    $objPerson->strFax         = $_POST['fax'];
    if ($_POST['submit']) {
      $result = $myDBAccess->updatePerson($objPerson);
      if (!empty($result)) {
        $_SESSION['uname'] = $objPerson->strEmail;
        $strMessage = 'Your account has been updated sucessfully.';
      }
      elseif ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error updating your account.', $myDBAccess->getLastError());
      }
      if (!empty($_POST['password1'])) {
        $result = $myDBAccess->updatePersonPassword(session('uid'), $_POST['password1']);
        if (!empty($result)) {
          $_SESSION['password'] = $result;
        }
        elseif ($myDBAccess->failed()) {
          error('Error updating your account.', $myDBAccess->getLastError());
        }
      }
    }
  }
}

$strContentAssocs['first_name']  = encodeText($objPerson->strFirstName);
$strContentAssocs['last_name']   = encodeText($objPerson->strLastName);
$strContentAssocs['email']       = encodeText($objPerson->strEmail);
$strContentAssocs['name_title']  = encodeText($objPerson->strTitle);
$strContentAssocs['affiliation'] = encodeText($objPerson->strAffiliation);
$strContentAssocs['street']      = encodeText($objPerson->strStreet);
$strContentAssocs['city']        = encodeText($objPerson->strCity);
$strContentAssocs['postalcode']  = encodeText($objPerson->strPostalCode);
$strContentAssocs['state']       = encodeText($objPerson->strState);
$strContentAssocs['country']     = encodeText($objPerson->strCountry);
$strContentAssocs['phone']       = encodeText($objPerson->strPhone);
$strContentAssocs['fax']         = encodeText($objPerson->strFax);
$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $strContentAssocs['if'] = array(9);
}
$strContentAssocs['targetpage'] = 'user_profile';
$content->assign($strContentAssocs);

$_SESSION['menu'] = 0;
$_SESSION['menuitem'] = 1;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Personal data of User '.encodeText(session('uname'));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Profile';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>