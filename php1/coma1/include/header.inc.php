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

//Debugging Einstellungen:
ini_set('error_reporting', '4095'); // E_ALL & E_STRICT
ini_set('display_errors', '1'); // später 0 ??
ini_set('display_startup_errors', '1'); // später 0 !!
ini_set('track_errors', '1');
ini_set('warn_plus_overloading', '1');

require_once('class.mysql.inc.php');
require_once('class.session.inc.php');
require_once('class.dbaccess.inc.php');

$mySql = new MySql();
checkError($mySql);

$mySession = new Session($mySql, 'coma1', 7200);
checkError($mySession);

$myDBAccess = new DBAccess($mySql);
checkError($myDBAccess);

?>
