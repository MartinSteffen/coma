<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

define('IN_COMA1', true);
define('NEED_NO_LOGIN', false);
require_once('./include/header.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$links = defaultAssocArray();

$content = new Template(TPLPATH.'profile.tpl');
$strContentAssocs = defaultAssocArray();  

// Lade die Daten der Person

$objPerson = $myDBAccess->getPersonDetailed($_SESSION['uid']);

// Teste, ob Daten mit der Anfrage des Benutzers mitgeliefert wurde.

if ((isset($_POST['action']))&&($_POST['action'] == 'update')) {
  $confirmFailed = false;
  $strMessage = '';

  /* Aktualisieren der Person in der Datenbank */
    
  // Teste, ob alle Pflichtfelder ausgefuellt wurden
  if ($_POST['last_name'] == '' || $_POST['email'] == '') {
    $strMessage = 'Sie m&uuml;ssen die Felder <b>Nachname</b> und <b>Email</b> ausf&uuml;llen!';
    $confirmFailed = true;
  }
  
  // [TODO] Darf die Email, also der Benutzername hier geaendert werden?!
  
  // Teste, ob die Email gueltig ist
  else if (!ereg("^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$", $_POST['email'])) {
    $strMessage = 'Geben Sie eine g&uuml;ltige Email-Adresse ein!';
    $confirmFailed = true;
  }
  // Teste, ob die Email bereits vorhanden ist  
  else if ((string)$_POST['email'] != (string)$_SESSION['uname'] &&
           $myDBAccess->checkEmail((string)$_POST['email']) == true) {
    $strMessage = 'Es existiert bereits ein Benutzer mit dieser Email-Adresse!';
    $confirmFailed = true;
  }  
  else {    
    $objPerson->strFirstName = $_POST['first_name'];
    $objPerson->strLastName = $_POST['last_name'];
    $objPerson->strEmail = $_POST['email'];
    $objPerson->strTitle = $_POST['name_title'];
    $objPerson->strStreet = $_POST['street'];
    $objPerson->strCity = $_POST['city'];
    $objPerson->strPostalCode = $_POST['postalcode'];
    $objPerson->strPhone = $_POST['phone'];
    $objPerson->strFax = $_POST['fax'];
  	
    $result = $myDBAccess->updatePerson($objPerson);
    if (empty($result)) {
      $strContentAssocs = defaultAssocArray();  
      $strMessage = 'Es ist ein Fehler beim Aktualisieren Ihrer Daten aufgetreten:<br>'.
                    $myDBAccess->getlastError(); 
      $confirmFailed = true;
    }
    else {
      $_SESSION['uname'] = $objPerson->strEmail;
      $strMessage = 'Ihre Daten sind erfolgreich ge&auml;ndert worden.';
    }
  }
}

$strContentAssocs['first_name'] = $objPerson->strFirstName;
$strContentAssocs['last_name'] = $objPerson->strLastName;
$strContentAssocs['email'] = $objPerson->strEmail;
$strContentAssocs['name_title'] = $objPerson->strTitle;
$strContentAssocs['street'] = $objPerson->strStreet;
$strContentAssocs['city'] = $objPerson->strCity;
$strContentAssocs['postalcode'] = $objPerson->strPostalCode;
$strContentAssocs['phone'] = $objPerson->strPhone;
$strContentAssocs['fax'] = $objPerson->strFax;

if (!empty($strMessage)) {
  $strContentAssocs['message'] = '<p class="message">'.$strMessage.'</p>';
}
else {
  $strContentAssocs['message'] = '';
}
$content->assign($strContentAssocs);  

$menu = new Template(TPLPATH.'mainmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['menu'] = array(1, 2, 3, 4);
$menu->assign($strMenuAssocs);

$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Pers&ouml;nliche Angaben von '.$_SESSION['uname'];
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;

$strPath = array($_SESSION['uname']=>'', 'Profil'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>