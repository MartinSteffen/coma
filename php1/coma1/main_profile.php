<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 * @todo Passwort aendern... (neues Formular?)
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
require_once('./include/header.inc.php');

$content = new Template(TPLPATH.'profile.tpl');
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
    $strMessage = 'You have to fill in the fields <b>Last name</b>, and <b>E-mail</b>!';
  }
  // Teste, ob die Email gueltig ist
  else if (!ereg("^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$", $_POST['email'])) {
    $strMessage = 'Please enter a valid E-mail address!';
  }
  // Teste, ob die Email bereits vorhanden ist
  else if ($_POST['email'] != $objPerson->strEmail &&
           $myDBAccess->checkEmail($_POST['email'])) {
    if ($myDBAccess->failed()) {
      error('Check e-mail failed.', $myDBAccess->getLastError());
    }
    $strMessage = 'Account with the given E-mail address is already existing! '.
                  'Please use enter another E-mail address!';
  }
  else {
    $objPerson->strFirstName   = encodeText($_POST['first_name']);
    $objPerson->strLastName    = encodeText($_POST['last_name']);
    $objPerson->strEmail       = encodeText($_POST['email']);
    $objPerson->strAffiliation = encodeText($_POST['affiliation']);
    $objPerson->strTitle       = encodeText($_POST['name_title']);
    $objPerson->strStreet      = encodeText($_POST['street']);
    $objPerson->strCity        = encodeText($_POST['city']);
    $objPerson->strPostalCode  = encodeText($_POST['postalcode']);
    $objPerson->strState       = encodeText($_POST['state']);
    $objPerson->strCountry     = encodeText($_POST['country']);
    $objPerson->strPhone       = encodeText($_POST['phone']);
    $objPerson->strFax         = encodeText($_POST['fax']);

    $result = $myDBAccess->updatePerson($objPerson);
    if (!empty($result)) {
      $_SESSION['uname'] = $objPerson->strEmail;
      $strMessage = 'Your account has been updated sucessfully.';
    }
    else if ($myDBAccess->failed()) {
      // Datenbankfehler?
      error('Error during updating account.', $myDBAccess->getLastError());
    }
  }
}

$strContentAssocs['first_name']  = decodeText($objPerson->strFirstName);
$strContentAssocs['last_name']   = decodeText($objPerson->strLastName);
$strContentAssocs['email']       = decodeText($objPerson->strEmail);
$strContentAssocs['name_title']  = decodeText($objPerson->strTitle);
$strContentAssocs['affiliation'] = decodeText($objPerson->strAffiliation);
$strContentAssocs['street']      = decodeText($objPerson->strStreet);
$strContentAssocs['city']        = decodeText($objPerson->strCity);
$strContentAssocs['postalcode']  = decodeText($objPerson->strPostalCode);
$strContentAssocs['state']       = decodeText($objPerson->strState);
$strContentAssocs['country']     = decodeText($objPerson->strCountry);
$strContentAssocs['phone']       = decodeText($objPerson->strPhone);
$strContentAssocs['fax']         = decodeText($objPerson->strFax);

$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $strContentAssocs['if'] = array(1);
}

$strContentAssocs['targetpage'] = 'main_profile';
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'mainmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(1);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Personal data of User '.session('uname');
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = session('uname').'  |  Profile';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>