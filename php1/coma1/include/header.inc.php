<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/**
 * Fehler in Klasse ueberpruefen und ausgeben
 *
 * Diese Funktion ueberprueft eine Klasse ob ein Fehler generiert wurde
 * und gibt diesen auch eventuell zurueck.
 *
 * @param object $class Ein coma1-Objekt das die Methode getLastError() unterstuetzt.
 */
function checkError(&$class) {
  $s = $class->getLastError();
  if (!empty($s)) {
    echo $s;
  }
}

/**
 * Verweis auf anderen Skript
 *
 * Diese Funktion lenkt den Benutzer auf einen anderen Skript weiter, in
 * dem die Bearbeitung fortgeführt wird. Dabei wird sichergestellt, das
 * Sessioninformationen erhalten bleiben.
 * WICHTIG: nur vor irgedwelchen Ausgaben aufrufen!
 *
 * @param string $strName Das aufzurufende Skript.
 */
function redirect($strName) {
  session_write_close();
  header('Location:'.COREURL.$strName);
}

/**
 * Erzeugen von Standard-Zuweisungen
 *
 * Die Funktion gibt ein Array mit Standard-Zuweisungen fuer
 * Templates zurueck!
 *
 * @return array Das geforderte Array
 */
function defaultAssocArray() {
  global $mySession;
  return array(
               'path'      => TPLURL,
               'basepath'  => COREURL,
               'dateiname' => basename($_SERVER['PHP_SELF'],'.php'),
               'SID'       => $mySession->getUrlId()
              );
}

// Debugging Einstellungen:
error_reporting(E_ALL);
ini_set('display_errors', '1');         // später 0 ??
ini_set('display_startup_errors', '1'); // später 0 !!
ini_set('warn_plus_overloading', '1');
// End Debugging

//if (isset($_SERVER['PATH_TRANSLATED'])) {
//  $ServerPathTranslated = $_SERVER['PATH_TRANSLATED'];
//}
//else {
  $ServerPathTranslated = realpath(dirname(__FILE__) . '../');
//}
echo $ServerPathTranslated;

// PFAD - Konstanten
/** Include-Pfad (als absolut)*/
define('INCPATH', $ServerPathTranslated.'/include/');
/** Das zu verwendende Design (Verzeichniss-Name)*/
define('DESIGN', 'simplecoma');
/** Template-Pfad (als absolut)*/
define('TPLPATH', $ServerPathTranslated.'/templates/'.DESIGN.'/');
/** Template-Pfad (als URL)*/
define('TPLURL', dirname($_SERVER['PHP_SELF']).'/templates/'.DESIGN.'/');
/** Haupt-Pfad (als URL)*/
define('COREURL', dirname($_SERVER['PHP_SELF']).'/');
// End PFAD - Konstanten

// Standard Klassen
require_once(INCPATH.'class.mysql.inc.php');
require_once(INCPATH.'class.session.inc.php');
require_once(INCPATH.'class.dbaccess.inc.php');
require_once(INCPATH.'class.template.inc.php');

$mySql = new MySql();
checkError($mySql);

$mySession = new Session($mySql, 'coma1', 7200);
checkError($mySession);

$myDBAccess = new DBAccess($mySql);
checkError($myDBAccess);
// End Standard Klassen

// Check ob User eingeloggt ist (nur wenn nicht login.php aufgerufen wird)
// Stellt ausserdem sicher das uid und password nur genau dann gesetzt sind,
// wenn der Benutzer korrekt eingeloggt ist!
if ((basename($_SERVER['PHP_SELF'])!='login.php')&&(!$myDBAccess->checkLogin())) {
  if (!isset($_SESSION['uname'])) {
    $_SESSION['message'] = 'Bitte melden Sie sich an!';
  }
  else {
    $_SESSION['message'] = 'Benutzername oder Passwort falsch!';
  }
  if (isset($_SESSION['password'])) {
    unset($_SESSION['password']);
  }
  if (isset($_SESSION['uid'])) {
    unset($_SESSION['uid']);
  }
  redirect('login.php');
}

?>
