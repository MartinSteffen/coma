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

/**
 * Klasse Session
 *
 * @author Jan Waller <jwa@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Sessions
 * @access public
 */
class Session {
  /**#@+
   * @access private
   */
  /**
   * @var MySql
   */
  var $mySql;
  /**
   * @var string
   */
  var $strError = '';
  /**#@-*/

  /**
   * Konstruktor
   *
   * Der Konstruktor erzeugt eine Verbindung mit der Datenbank und initilisiert 
   * die Session-Daten.
   *
   * @return bool <b>true</b> bei Erfolg, <b>false</b> bei Fehler
   * @see error()
   * @see getLastError()
   */
  function DBAccess() {
    $this->mySql = new MySql();
    // FehlerCheck
    $s = $this->mySql->getLastError();
    if (!empty($s)) {
      return $this->error('Fehler beim Instanziieren. '.$s);
    }
      
    session_name($sessionName);
    session_cache_limiter('nocache');
    session_start();

    return true;
  }

  /**
   * Fehlerbeschreibung festlegen
   *
   * @param string $text Optionale Fehlerbeschreibung.
   * @return false Es wird immer <b>false</b> zurueckgegeben.
   * @see getLastError()
   * @access protected
   */
  function error($strError='') {
    $this->strError = "[Session: $strError ]";
    return false;
  }

  /**
   * Letzten Fehler zurueckgeben
   *
   * Die Funktion <b>getLastError()</b> gibt die letzte mit error
   * gesicherte Fehlermeldung zurueck und loescht diese aus dem Speicher.
   *
   * @return string Die letzte Fehlermeldung.
   * @see error()
   * @access public
   */
  function getLastError() {
    $strError = $this->strError;
    $this->strError = '';
    return $strError;
  }
  
  /**
   * @access private
   */
  function sessionOpen($save_path, $sess_name) {
    
  }
  
  /**
   * @access private
   */
  function sessionRead($sess_id) {
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