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
 * Fehler auf hoechster Ebene abfangen und ausgeben
 *
 * @param string $strMethod Methode in der der Fehler aufgetreten ist
 * @param string $strError Beschreibung des Fehlers
 * @param string $strComment optionaler weiterer Kommentar
 * @return bool immer <b>false</b>
 * @todo Fehlerausgabe verbessern
 */
function error($strMethod, $strError, $strComment='') {
  $strComment = empty($strComment) ? '' : " ($strComment)";
  $strError = "[COMA->$strMethod: $strError$strComment]";
  die($strError);
  return false;
}

/**
 * Weiterleitung auf ein anderes Skript
 *
 * Diese Funktion lenkt den Benutzer auf einen anderen Skript weiter, in
 * dem die Bearbeitung fortgeführt wird. Dabei wird sichergestellt, das
 * Sessioninformationen erhalten bleiben.
 * WICHTIG: nur vor irgendwelchen Ausgaben aufrufen!
 *
 * @param string $strName Das aufzurufende Skript.
 */
function redirect($strName) {
  global $mySession;
  session_write_close();
  header('Location:' . COREURL . $strName . '?' .$mySession->getUrlId());
  die();
}

/**
 * Erzeugen von Standard-Zuweisungen fuer Templates
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
               'filename'  => basename($_SERVER['PHP_SELF'],'.php'),
               'SID'       => $mySession->getUrlId()
              );
}

/**
 * geschuetztes Lesen von Session-Variablen
 *
 * Diese Funktion liest eine Sessionvariable aus, und stellt dabei sicher
 * das diese auch gesetzt ist! Im Fehlerfall wird der Benutzer 
 * normalerweise auf die index-Seite geleitet.
 * Ist jedoch Parameter $blnRedirect==false, so wird stattdessen '' 
 * geliefert.
 *
 * @param string $strName Der Name der Variablen.
 * @param bool $blnRedirect Soll eine Weiterleitung stattfinden
 * @return string Wert der Variablen
 */
function session($strName, $blnRedirect=true) {
  if (!isset($_SESSION[$strName])) {
    if (!$blnRedirect) {
      return '';
    }
    redirect('index.php');
  }
  else {
    return $_SESSION[$strName];
  }
}

/**
 * Loeschen von Session-Variablen
 *
 * Diese Funktion loescht eine Sessionvariable aus dem Speicher
 *
 * @param string $strName Der Name der Variablen.
 */
function session_delete($strName) {
  if (isset($_SESSION[$strName])) {
    unset($_SESSION[$strName]);
  }
}

// Debugging Einstellungen:
error_reporting(E_ALL);
ini_set('display_errors', '1');         // später 0 ??
ini_set('display_startup_errors', '1'); // später 0 !!
ini_set('warn_plus_overloading', '1');
// End Debugging

// PATH_TRANSLATED patch
$ServerPathTranslated = realpath(dirname(__FILE__) . '/../');

// PFAD - Konstanten
/** Include-Pfad (als absolut)*/
define('INCPATH', $ServerPathTranslated.'/include/');
/** Das zu verwendende Design (Verzeichniss-Name)*/
define('DESIGN', 'coma-2');
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

$mySql = new MySql();
if ($mySql->failed()) {
  error('Erzeugen den Standard-Objekte',$mySql->getLastError());
}

$mySession = new Session($mySql, 'coma1', 7200);
if ($mySession->failed()) {
  error('Erzeugen den Standard-Objekte',$mySession->getLastError());
}

$myDBAccess = new DBAccess($mySql);
if ($myDBAccess->failed()) {
  error('Erzeugen den Standard-Objekte',$myDBAccess->getLastError());
}
// End Standard Klassen

/**#@+ Konstanten fuer die Rollenverteilung */
define('CHAIR',       2);
define('REVIEWER',    3);
define('AUTHOR',      4);
define('PARTICIPANT', 5);
$intRoles = array(CHAIR, REVIEWER, AUTHOR, PARTICIPANT);
$strRoles = array(CHAIR       => 'Chair',
                  REVIEWER    => 'Reviewer',
                  AUTHOR      => 'Author',
                  PARTICIPANT => 'Participant');
/**#@-*/

// Check, ob User eingeloggt ist (nur wenn nicht login.php aufgerufen wird)
// Stellt ausserdem sicher, dass uname und password nur genau dann gesetzt sind,
// wenn der Benutzer korrekt eingeloggt ist!
if (!defined('NEED_NO_LOGIN')) {
  if ($myDBAccess->checkLogin()) {
    if (!isset($_SESSION['uid'])) {
      // UID setzen
      $_SESSION['uid'] = $myDBAccess->getUserIdByEmail(session(uname));
      if ($myDBAccess->failed()) {
        session_delete('uid');
        error('checkLogin',$myDBAccess->getLastError());
      }
    }
  }
  else {
    // nicht korrekt eingeloggt
    session_delete('password');
    session_delete('uname');
    session_delete('uid');
    session_delete('confid');
    if ($myDBAccess->failed()) {
      error('checkLogin',$myDBAccess->getLastError());
    }
    if (!isset($_SESSION['uname'])) {
      $_SESSION['message'] = 'Bitte melden Sie sich an!';
    }
    else {
      $_SESSION['message'] = 'Benutzername oder Passwort falsch!';
    }
    redirect('login.php');
  }
}
?>