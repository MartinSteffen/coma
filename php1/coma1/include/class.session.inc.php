<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

// besser ueber DBAccess??
require_once('class.mysql.inc.php');

mySql = new MySql();
// FehlerCheck
$s = $mySql->getLastError();
if (!empty($s)) {
  // Stop bei Fehler!?!?
  exit('Fehler beim Instanziieren. '.$s);
}

session_name('coma1');
session_cache_limiter('nocache');
if (!session_set_save_handler('sessionOpen', 'sessionClose' 'sessionRead' 'sessionWrite' 'sessionDestroy' 'sessionGC')) {
  exit('Konnte Sessionmanger nicht initialisieren (save_handler).');
}
session_start();

/**
* @param string $save_path Session Speicher Pfad
* @param string $sess_name Alphanumerischer Sessions-Name.
* @return bool <b>true</b> bei Erfolg, sonst <b>false</b>.
* @access private
*/
function sessionOpen($save_path, $sess_name) {
  global $sess_path, $sess_name, $sess_maxlife;

  $sess_path = $path;
  $sess_name = $name;
  return true;
}

/**
* @retrun true Die Funktion gibt immer <b>true</b> zurueck.
* @access private
*/
function sessionClose() {
return true;
}

/**
* @access private
*/
function sessionRead($sess_id) {
  $retArray = mySql->select("SELECT sessdata FROM sessions WHERE sid='$sess_id' AND sname='$sess_name'";
}

/**
* @access private
*/
function sessionWrite($sess_id, $val) {
}

/**
* @access private
*/
function sessionDestroy($sess_id) {
}

/**
* @access private
*/
function sessionGC($max_lifetime) {
}

?>