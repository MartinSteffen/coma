<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 * @todo Darf die Email, also der Benutzername hier geaendert werden?!
 * @todo Fehlende Felder ergaenzen!
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

// Teste, ob Daten mit der Anfrage des Benutzers mitgeliefert wurde.
if ((isset($_POST['action']))&&($_POST['action'] == 'update')) {

  // Teste, ob alle Pflichtfelder ausgefuellt wurden
  if (empty($_POST['last_name'])
  ||  empty($_POST['email'])) {
    $strMessage = 'Sie m&uuml;ssen die Felder <b>Nachname</b>, und <b>Email</b>'
                 .'ausf&uuml;llen!';
  }
  // @TODO Darf die Email, also der Benutzername hier geaendert werden?!
  // Teste, ob die Email gueltig ist
  elseif (!ereg("^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$", $_POST['email'])) {
    $strMessage = 'Geben Sie eine g&uuml;ltige Email-Adresse ein!';
  }
  // Teste, ob die Email bereits vorhanden ist
  elseif ($_POST['email'] != session('uname')
      &&  $myDBAccess->checkEmail($_POST['email'])) {
    $strMessage = 'Es existiert bereits ein Benutzer mit dieser Email-Adresse!';
  }
  else {
    $objPerson->strFirstName  = $_POST['first_name'];
    $objPerson->strLastName   = $_POST['last_name'];
    $objPerson->strEmail      = $_POST['email'];
    $objPerson->strTitle      = $_POST['name_title'];
    $objPerson->strStreet     = $_POST['street'];
    $objPerson->strCity       = $_POST['city'];
    $objPerson->strPostalCode = $_POST['postalcode'];
    $objPerson->strPhone      = $_POST['phone'];
    $objPerson->strFax        = $_POST['fax'];

    $result = $myDBAccess->updatePerson($objPerson);
    if (!empty($result)) {
      $_SESSION['uname'] = $objPerson->strEmail;
      $strMessage = 'Ihre Daten sind erfolgreich ge&auml;ndert worden.';
    }
    else {
      $strMessage = 'Es ist ein Fehler beim Aktualisieren Ihrer Daten aufgetreten:<br>'
                   .$myDBAccess->getlastError();
    }
  }
}


$strContentAssocs['first_name'] = $objPerson->strFirstName;
$strContentAssocs['last_name']  = $objPerson->strLastName;
$strContentAssocs['email']      = $objPerson->strEmail;
$strContentAssocs['name_title'] = $objPerson->strTitle;
$strContentAssocs['street']     = $objPerson->strStreet;
$strContentAssocs['city']       = $objPerson->strCity;
$strContentAssocs['postalcode'] = $objPerson->strPostalCode;
$strContentAssocs['phone']      = $objPerson->strPhone;
$strContentAssocs['fax']        = $objPerson->strFax;

$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $strContentAssocs['if'] = array(1);
}

$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'mainmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(1);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Pers&ouml;nliche Angaben von '.session('uname');
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;

$strPath = array(session('uname')=>'', 'Profil'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>