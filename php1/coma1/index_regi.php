<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1',true);
require_once('./include/header.inc.php');

// Testen, ob Daten mit der Anfrage des Benutzer mitgeliefert wurde.
if (isset($_POST['email'])){
  $mainPage = new Template(TPLPATH.'main.tpl');
  $menue = new Template(TPLPATH.'nav_index.tpl');
  $loginPage = new Template(TPLPATH.'empty.tpl');

  $strMainAssocs = defaultAssocArray();
  $strMainAssocs['titel'] = ' Willkommen bei CoMa - dem Konferenzmanagement-Tool ';
  $strMainAssocs['content'] = 
  '
  <div align="center">
  <table>
    <tr> 
     <td>
         Titel: <br>
         Vorname:  <br>
         Nachname:  <br>
         Email: <br>
         Telefon: <br>
         Fax: <br>
         Stra&szlig;e: <br>
         PLZ: <br>
         Ort: <br>
      </td>
      <td> 
        '.$_POST['title'].'<br>'.
          $_POST['first_name'].'<br>'.
          $_POST['last_name'].'<br>'.
          $_POST['email'].'<br>'.
          $_POST['telefon'].'<br>'. 
          $_POST['fax'].'<br>'.
          $_POST['street'].'<br>'.
          $_POST['zip'].'<br>'.
          $_POST['city'].'<br>
     </td>
    </tr>
   </tabel>     
   ';

  /**
   *  Anlegen der Person in der Datenbank
   *
   */
  // Test, ob alle Pflichtfelder ausgefüllt wurden
  if ( $_POST['last_name']=='' || $_POST['email']=='' || $_POST['userPassword']==''){
   $strMessage = ' Email, Passwort und Nachname m&uuml;ssen angegeben werden !!! ' ;
   $strMainAssocs['message'] = $strMessage; 
  }
  // Test, ob Passwort mit der Wiederholung übereinstimmt
  else if ( $_POST['userPassword'] !=  $_POST['userPassword2']){
    $strMessage = ' Passwort stimmt nicht mir der Wiederholung &uuml;berein !!! ' ;
    $strMainAssocs['message'] = $strMessage; 
    }
  // Test, ob die Email gültig ist
  else if (!($_POST['email']!="" && 
	     ereg("^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$", $_POST['email']))){
    $strMessage = ' Bitte geben Sie eine g&uuml;ltige Emailadresse ein !!! ' ;
    $strMainAssocs['message'] = $strMessage; 

    }
  // Test, ob die Email bereits vorhanden ist
  else if ( $myDBAccess->checkEmail((string)$_POST['email']) == true){
    $strMessage = ' Ihre Emailadresse ist bereits registriert !!! ' ;
    $strMainAssocs['message'] = $strMessage; 
    }
  else {
    $msg=$myDBAccess->addPerson( $_POST['first_name'], 
                                 $_POST['last_name'], 
                                 $_POST['email'],
                                 $_POST['title'],
                                 '', 
                                 $_POST['street'], 
                                 $_POST['city'],
                                 $_POST['zip'], 
                                 '','',
                                 $_POST['telefon'], 
                                 $_POST['fax'], 
                                 $_POST['userPassword']);
    
     if ($msg == false){
     $strMessage = 'Ein Fehler beim Anlegen des Benutzers in der Datenbank ist aufgetreten ' ;
     $strMainAssocs['message'] = $strMessage; 
     }
     else {
     $strMessage = 'Benutzer erfolgreich angelegt, bitte <a href="index.php"> einloggen</a> ' ;
     $strMainAssocs['message'] = $strMessage; 
     }
  }




  $loginPage->assign($strMainAssocs);

  $strMainAssocs['body'] = & $loginPage;
  $strMainAssocs['menue'] =& $menue;
  $strMainAssocs['submenue'] = '';

  $mainPage->assign($strMainAssocs);
  $menue->assign(defaultAssocArray());
  $menue->assign($strMenueAssocs);
  $mainPage->parse();
  $mainPage->output();




} 

 // Wurde mit der Anforderung dieser Seite wurden keine POST-Daten mitgeliefert,
 // dann wird das Registrierungsformular angezeigt.
 else {
   $mainPage = new Template(TPLPATH.'main.tpl');
   $menue = new Template(TPLPATH.'nav_index.tpl');
   $loginPage = new Template(TPLPATH.'register.tpl');
   $emptyPage = new Template(TPLPATH.'empty.tpl');

   $strMainAssocs = defaultAssocArray();
   $strMainAssocs['titel'] = ' Willkommen bei CoMa - dem Konferenzmanagement-Tool ';
   $strMainAssocs['content'] = '
   <h2 align="center"> Hinweis: Bitte registrieren Sie sich nur einmal ! </h2>';   
   $strMainAssocs['body'] = & $loginPage;
   $strMainAssocs['menue'] =& $menue;
   $strMainAssocs['submenue'] = '';
   
   // Testet, ob der Benutzer bereits eingeloggt ist
   if (isset($_SESSION['message'])) {
     $strMessage = $_SESSION['message'];
     unset($_SESSION['message']);
   }
   else if ((isset($_SESSION['uid']))||(isset($_SESSION['password']))) {
     $strMessage = 'Sie sind bereits eingeloggt !!!';
     $strMainAssocs['body'] = & $emptyPage;
   }
   else {
     $strMessage = '';
   }

   $strLoginAssocs = defaultAssocArray();
   $strLoginAssocs['message'] = $strMessage;
   $mainPage->assign($strMainAssocs);
   $menue->assign(defaultAssocArray());
   $menue->assign($strMenueAssocs);

   $loginPage->assign($strLoginAssocs);
   $emptyPage->assign($strLoginAssocs);

   $mainPage->parse();
   $mainPage->output();
  }

?>
