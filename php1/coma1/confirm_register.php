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

// Teste, ob Daten mit der Anfrage des Benutzer mitgeliefert wurde.
if (isset($_POST['email'])){
  $main = new Template(TPLPATH.'frame.tpl');
  $links = defaultAssocArray();

  $content = new Template(TPLPATH.'confirm_register.tpl');
  $strContentAssocs = defaultAssocArray();  
  $confirmFailed = false;
  $strMessage = '';

  /* Anlegen der Person in der Datenbank */
  
  // Teste, ob alle Pflichtfelder ausgefuellt wurden
  if ($_POST['last_name'] == '' || $_POST['email'] == '' || $_POST['user_password'] == '' ||
      $_POST['password_repeat'] == '') {    
    $strMessage = 'Sie m&uuml;ssen die Felder <b>Nachname</b>, <b>Email</b> und <b>Passwort</b>'.
                  'ausf&uuml;llen!';
    $confirmFailed = true;
  }
  // Teste, ob Passwort mit der Wiederholung uebereinstimmt
  else if ( $_POST['user_password'] !=  $_POST['password_repeat']) {
    $strMessage = 'Ihr Passwort stimmt nicht mit der Wiederholung des Passwortes &uuml;berein!';
    $confirmFailed = true;
  }
  // Teste, ob die Email gueltig ist
  else if (!ereg("^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$", $_POST['email'])) {
    $strMessage = 'Geben Sie eine g&uuml;ltige Email-Adresse ein!';
    $confirmFailed = true;
  }
  // Teste, ob die Email bereits vorhanden ist
  else if ( $myDBAccess->checkEmail((string)$_POST['email']) == true) {
    $strMessage = 'Es existiert bereits ein Benutzer mit dieser Email-Adresse! '.
                  'Verwenden Sie bitte diesen Account oder geben Sie eine andere Email-Adresse ein.';
    $confirmFailed = true;
  }
  else {   
    $result = $myDBAccess->addPerson( $_POST['first_name'], 
                                      $_POST['last_name'], 
                                      $_POST['email'],
                                      $_POST['name_title'], '', 
                                      $_POST['street'], 
                                      $_POST['city'],
                                      $_POST['postalcode'], '', '',
                                      $_POST['phone'], 
                                      $_POST['fax'], 
                                      $_POST['user_password']);     
    
     if (empty($result)) {
       $strContentAssocs = defaultAssocArray();  
       $strMessage = 'Es ist ein Fehler beim'; 
       $confirmFailed = true;
     }
     else {
       $strContentAssocs['first_name'] = $_POST['first_name'];
       $strContentAssocs['last_name'] = $_POST['last_name'];
       $strContentAssocs['email'] = $_POST['email'];
       $strContentAssocs['name_title'] = $_POST['name_title'];
       $strContentAssocs['street'] = $_POST['street'];
       $strContentAssocs['city'] = $_POST['city'];
       $strContentAssocs['postalcode'] = $_POST['postalcode'];
       $strContentAssocs['phone'] = $_POST['phone'];
       $strContentAssocs['fax'] = $_POST['fax'];
       $strContentAssocs['user_password'] = $_POST['user_password'];
       $strMessage = 'Der Benutzer wurde erfolgreich angelegt! Bitte loggen Sie sich auf der '.
                     '<a href="'.$links['basepath'].'index.php?'.$links['SID'].'">Startseite</a> '.
                     'mit ihrer Email-Adresse als Benutzernamen ein!';
     }
  }
  
  if (!empty($confirmFailed)) {
    $content = new Template(TPLPATH.'register.tpl');
  }
  
  $strContentAssocs['message'] = '<p class="message">$strMessage</p>';
  $content->assign(defaultAssocArray());  

  $strMainAssocs = defaultAssocArray();
  $strMainAssocs['title'] = 'Neuen Benutzer registrieren';
  $strMainAssocs['content'] = &$content;

  require_once(TPLPATH.'startmenu.php');
  $strMainAssocs['menu'] = openStartMenuItem(1);

  $strPath = array('CoMa'=>'', 'Registrieren'=>'');
  require_once(TPLPATH.'navigatoritem.php');
  $strMainAssocs['navigator'] = createNavigatorContent($strPath);

  $main->assign($strMainAssocs);
  $main->parse();
  $main->output();
} 
else {
  redirect('login.php');
}

?>