<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

/**
 * Fehler auf hoechster Ebene abfangen und ausgeben
 *
 * @param string $strMethod Methode in der der Fehler aufgetreten ist
 * @param string $strError Beschreibung des Fehlers
 * @param string $strComment optionaler weiterer Kommentar
 */
function error($strMethod, $strError, $strComment='') {
  $strComment = empty($strComment) ? '' : " ($strComment)";
  $strError = '['.basename($_SERVER['PHP_SELF'],'.php')."->$strMethod: $strError$strComment]";
  include(TPLPATH.'error.php');
  echo($strError);
  die(1);
}

/**
 * Weiterleitung auf ein anderes Skript
 *
 * Diese Funktion lenkt den Benutzer auf einen anderen Skript weiter, in
 * dem die Bearbeitung fortgeführt wird. Dabei wird sichergestellt, das
 * Sessioninformationen erhalten bleiben.
 *
 * @warning nur vor irgendwelchen Ausgaben aufrufen!
 * @param string $strName Das aufzurufende Skript.
 */
function redirect($strName) {
  global $mySession;
  session_write_close();
  header('Location:' . COREURL . $strName . $mySession->getUrlId('?'));
  die(0);
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
               '?SID'       => $mySession->getUrlId('?'),
               '&SID'       => $mySession->getUrlId('&')
              );
}

/**
 * This function encodes the string.
 *
 * You can safetly use this function to save its result in a
 * database. It eliminates any space in the beginning ou end
 * of the string, HTML and PHP tags, and encode any special
 * char to the usual HTML entities (&[...];), eliminating the
 * possibility of bugs in inserting data on a table
 *
 * @param string $_str String to encode
 * @return string encoded string
 */
function encodeText($_str) {
  $trans_tbl = get_html_translation_table(HTML_ENTITIES);
  $trans_tbl["\\"] = "\\\\";
  $trans_tbl["\x00"] = "\\\x00";
  $trans_tbl["\n"] = '<BR>';
  $trans_tbl["\r"] = '';
  $trans_tbl["\t"] = ' ';
  $trans_tbl["'"] = '&#039;';
  $trans_tbl["\x1a"] = "\\\x1a";
  $trans_tbl['"'] = '&#039;'; // keine Doppelquotes zulassen!
  $_str = strtr($_str, $trans_tbl);
  $_str = trim($_str);
  return($_str);
}

/**
 * This function decodes the string.
 *
 * Was ist mit quotes? " ist nicht benutzbar!!
 *
 * @param string $_str String to decode
 * @return string decoded string
 */
function decodeText($_str) {
  $trans_tbl = get_html_translation_table (HTML_ENTITIES);
  $trans_tbl["\\"] = "\\\\";
  $trans_tbl["\x00"] = "\\\x00";
  $trans_tbl["\n"] = '<BR>';
  $trans_tbl["'"] = '&#039;';
  $trans_tbl["\x1a"] = "\\\x1a";
  $trans_tbl = array_flip($trans_tbl);
  $_str = strtr($_str, $trans_tbl);
  return($_str);
}

/**
 * This function makes an encoded String URL valid (gives an valid Link!)
 *
 * @param string $_str String to encode
 * @return string encoded string
 */
function encodeURL($_str) {
  $_str = decodeText($_str);
  $_str = str_replace('\'', urlencode('\''), $_str);
  $_str = str_replace('"', urlencode('"'), $_str);
  if (!preg_match('#^http[s]?:\/\/#i', $_str)) {
    $_str = 'http://' . $_str;
  }
  if (!preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $_str))
  {
    $_str = '';
  }
  return($_str);
}

// Debugging Einstellungen:
error_reporting(E_ALL);
ini_set('display_errors', '1');         // später 0 ??
ini_set('display_startup_errors', '1'); // später 0 !!
ini_set('warn_plus_overloading', '1');
// End Debugging

// Magic Quoatas machen uns eh nur Aerger... Verbieten?
ini_set('magic_quotes_runtime', '0');
ini_set('magic_quotes_sybase', '0');
if (get_magic_quotes_gpc()) {
  error('PHP-Server Config', '\'magic_quotes_gpc\' should be set to off!');
}

// PATH_TRANSLATED patch
$ServerPathTranslated = realpath(dirname(__FILE__) . '/../');

// PFAD - Konstanten
/** Include-Pfad (als absolut)*/
define('INCPATH', $ServerPathTranslated.'/include/');
/** Das zu verwendende Design (Verzeichnis-Name)*/
define('DESIGN', 'coma');
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
// Nur hier weil das eh jeder braucht, eventuell besser in jeden einzelnen rein!!
require_once(INCPATH.'class.template.inc.php');

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
  if ($myDBAccess->checkLogin(session('uname',false), session('password', false))) {
    if (!isset($_SESSION['uid'])) {
      // UID setzen
      $_SESSION['uid'] = $myDBAccess->getPersonIdByEmail(session('uname'));
      if ($myDBAccess->failed()) {
        session_delete('uid');
        error('checkLogin',$myDBAccess->getLastError());
      }
    }
  }
  else {
    // nicht korrekt eingeloggt
    if ($myDBAccess->failed()) {
      error('checkLogin',$myDBAccess->getLastError());
    }
    if (!isset($_SESSION['uname'])) {
      $_SESSION['message'] = 'Please login with your Username and Password!';
    }
    else {
      $_SESSION['message'] = 'Username or Password is wrong!';
    }
    session_delete('uname');
    session_delete('password');
    session_delete('uid');
    session_delete('confid');
    redirect('login.php');
  }
}
?>