<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 * @todo state, country, affilation
 */
/***/

define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
require_once('./include/header.inc.php');

$content = new Template(TPLPATH.'register.tpl');
$strContentAssocs = defaultAssocArray();

// Teste, ob Daten mit der Anfrage des Benutzer mitgeliefert wurde.
if (isset($_POST['email'])){

  // Anlegen der Person in der Datenbank
  $strContentAssocs['first_name'] = $_POST['first_name'];
  $strContentAssocs['last_name']  = $_POST['last_name'];
  $strContentAssocs['email']      = $_POST['email'];
  $strContentAssocs['name_title'] = $_POST['name_title'];
  $strContentAssocs['street']     = $_POST['street'];
  $strContentAssocs['city']       = $_POST['city'];
  $strContentAssocs['postalcode'] = $_POST['postalcode'];
  $strContentAssocs['phone']      = $_POST['phone'];
  $strContentAssocs['fax']        = $_POST['fax'];

  // Teste, ob alle Pflichtfelder ausgefuellt wurden
  if (empty($_POST['last_name'])
  ||  empty($_POST['email'])
  ||  empty($_POST['user_password'])
  ||  empty($_POST['password_repeat'])) {
    $strMessage = 'Sie m&uuml;ssen die Felder <b>Nachname</b>, <b>Email</b> und <b>Passwort</b> '
                 .'ausf&uuml;llen!';
  }
  // Teste, ob Passwort mit der Wiederholung uebereinstimmt
  elseif ($_POST['user_password'] != $_POST['password_repeat']) {
    $strMessage = 'Ihr Passwort stimmt nicht mit der Wiederholung des Passwortes &uuml;berein!';
  }
  // Teste, ob die Email gueltig ist
  elseif (!ereg("^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$", $_POST['email'])) {
    $strMessage = 'Geben Sie eine g&uuml;ltige Email-Adresse ein!';
  }
  // Teste, ob die Email bereits vorhanden ist
  elseif ($myDBAccess->checkEmail($_POST['email'])) {
    $strMessage = 'Es existiert bereits ein Benutzer mit dieser Email-Adresse! '
                 .'Verwenden Sie bitte diesen Account oder geben Sie eine andere Email-Adresse ein.';
  }
  // Versuche einzutragen
  else {
    $result = $myDBAccess->addPerson($_POST['first_name'],
                                     $_POST['last_name'],
                                     $_POST['email'],
                                     $_POST['name_title'],
                                     '',
                                     $_POST['street'],
                                     $_POST['city'],
                                     $_POST['postalcode'],
                                     '',
                                     '',
                                     $_POST['phone'],
                                     $_POST['fax'],
                                     $_POST['user_password']);
    if (!empty($result)) {
      // Erfolg (also anderes Template)
      $content = new Template(TPLPATH.'confirm_register.tpl');
    }
    else {
      // Datenbankfehler?
      $strMessage = 'Es ist ein Fehler beim Ausf&uuml;hren der Registrierung aufgetreten:<br>'
                   .$myDBAccess->getLastError()
                   .'<br>Bitte versuchen Sie erneut, sich zu registrieren.';


    }
  }
}

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
$strMainAssocs['title'] = 'Neuen Benutzer registrieren';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;

$strPath = array('CoMa'=>'', 'Registrieren'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();
?>