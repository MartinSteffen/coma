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

require_once('config.inc.php');

/**@ignore */
if (!defined('DEBUG')) {
  define('DEBUG', false);
}
/**@ignore */
define('VERSION', '0.M2');


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

// alle huebschen Funktionen
require_once(INCPATH.'lib.inc.php');

// Zeit merken
chronometer();

// Header fuer die korrekte Ausgabe
header('Content-type: text/html; charset=utf-8');

// Debugging Einstellungen:
if (DEBUG) {
  error_reporting(E_ALL);
  ini_set('display_errors', '1');         // spaeter 0 ??
  ini_set('display_startup_errors', '1'); // spaeter 0 !!
  ini_set('warn_plus_overloading', '1');
}
// End Debugging

// Magic Quotes machen uns eh nur Aerger... Verbieten?
ini_set('magic_quotes_runtime', '0');
ini_set('magic_quotes_sybase', '0');
if (get_magic_quotes_gpc()) {
  error('PHP-Server Config', '\'magic_quotes_gpc\' should be set to off!');
}

// check auf INSTALL.PHP
if (!DEBUG) {
  if (@file_exists($ServerPathTranslated . '/INSTALL.PHP')) {
    error('INSTALL.PHP', 'You have to delete the file INSTALL.PHP in order to use this tool (security reasons!)');
  }
}

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

// Check, ob User eingeloggt ist
// Stellt ausserdem sicher, dass uname und password nur genau dann gesetzt sind,
// wenn der Benutzer korrekt eingeloggt ist!
// Setzt uid korrekt
if (!defined('NEED_NO_LOGIN')) {
  if (checkLogin()) {
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
    if (!isset($_SESSION['uname'])) {
      $_SESSION['message'] = 'Please login with your Username (E-mail) and Password!';
    }
    session_delete('uname');
    session_delete('password');
    session_delete('uid');
    session_delete('confid');
    redirect('login.php');
  }
}
?>
