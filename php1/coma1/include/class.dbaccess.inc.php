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
  /**
   * @access private
   * @var MySql
   */
  var $mySql;

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
    if (empty($s)) {
      echo('Leer');
    }
    else {
      echo($s);
    }

    return true;
  }

}

?>