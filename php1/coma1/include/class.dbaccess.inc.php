<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

require_once('class.mysql.inc.php');

/**
 * Klasse DBAccess
 *
 * @author Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @author Tom Scherzer <tos@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, PHP1
 * @package coma1
 * @subpackage DBAccess
 * @access protected
 */
class DBAccess {
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
   * Der Konstruktor erzeugt eine Verbindung mit der Datenbank.
   *
   * @return bool <b>true</b> bei Erfolg, <b>false</b> bei Fehler
   * @see error()
   * @see getLastError()
   */
  function DBAccess() {
    $this->mySql = new MySql();

    $s = $this->mySql->getLastError();
    if (!empty($s)) {
      return this->error('Fehler beim Instanziieren. '.$s);
    }

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
  function error($strText='') {
    $this->strError = '[DBAccess: '.$strText.']';
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
}

?>