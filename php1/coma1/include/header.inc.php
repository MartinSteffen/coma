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

// Debugging Einstellungen:
error_reporting(E_ALL);
ini_set('display_errors', '1'); // später 0 ??
ini_set('display_startup_errors', '1'); // später 0 !!
ini_set('warn_plus_overloading', '1');
// End Debugging

// PFAD - Konstanten
/** Include-Pfad (als absolut)*/
define('INCPATH',dirname($_SERVER['PATH_TRANSLATED']).'/include/');
/** Template-Pfad (als absolut)*/
define('TPLPATH',dirname($_SERVER['PATH_TRANSLATED']).'/templates/');
/** Haupt-Pfad (als URL)*/
define('COREPATH',dirname($_SERVER['PHP_SELF']).'/');
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
if ((basename($_SERVER['PHP_SELF'])!='login.php')&&($myDBAccess->checkLogin())) {
  session_write_close();
  header('Location:'.COREPATH.'login.php');
}

?>
