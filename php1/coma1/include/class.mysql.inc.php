<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/**
 * Klasse MySql
 *
 * Eine simple Klasse die einfachste Funktionen fuer MySql
 * breitstellt, wie z.B. connect, select, insert;
 * sowie einfache Fehlerbehandlungsroutinen
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Jan Waller
 * @package coma1
 * @subpackage DBAccess
 * @access protected
 *
 */
class MySql {

  /**#@+
   * @access private
   * @var string
   */
  var $mySqlServer = 'localhost';
  var $mySqlUser = '';
  var $mySqlPassword = '';
  var $mySqlDatabase = '';
  var $errString;
  /**#@-*/
  /**
   * Ein Verweiss auf das aktuelle Handle der Datenbank.
   * Sollte nur in Ausnahmefaellen direkt genutzt werden!
   *
   * @access protected
   * @var resource
   */
  var $conn;

  /**
   * Konstruktor
   *
   * Der Konstruktor stellt eine Verbindung mit der Datenbank her und
   * stellt die Databse ein. Die Daten werden dabei aus der Konfigurations
   * datei gelesen.
   *
   * @return bool <b>true</b> bei Erfolg <b>false</b> falls ein Fehler auftrat
   * @see error()
   * @see getLastError()
   *
   */
  function MySql() {
    require_once('config.inc.php');
    $this->mySqlServer = $sqlServer;
    $this->mySqlUser = $sqlUser;
    $this->mySqlPassword = $sqlPassword;
    $this->mySqlDatabase = $sqlDatabase;

    // mysql_pconnect ???? Was ist besser? - Jan
    $conn = @mysql_connect($sqlServer, $sqlUser , $sqlPassword);
    if (!$conn) {
      return $this->error("Could not connect to MySQL: ");
    }

    if (!mysql_select_db($sqlDatabase)) {
      return $this->error("Could not select Database: ");
    }
    $this->conn = $conn;
    return true;
  }

  /**
   * select()
   *
   * Die Funktion <b>select()</b> ermoeglicht select Anfragen an die Datenbank.
   * Dabei werden einfache Fehlerchecks durchgefuert. Das Ergebniss wird
   * automatisch in ein array umgewandelt.
   *
   * @param string $sql Eine SQL <b>select</b> Anfrage an die Datenbank
   * @return array|false Eine Liste der Ergebnisse oder <b>false</b> falls ein Fehler auftrat.
   * @see error()
   * @see getLastError()
   * @access public
   *
   */
  function select($sql='') {
    if (empty($sql)) {
      return false;
    }
    if (!eregi("^select",$sql)) {
      return $this->error("MySql->select called with $sql");
    }
    if (empty($this->conn)) {
      return false;
    }
    $results = mysql_query($sql, $this->conn);
    if (empty($results)) {
      @mysql_free_result($results);
      return false;
    }
    $count = 0;
    $data = array();
    while ($row = mysql_fetch_array($results)) {
      $data[$count] = $row;
      $count++;
    }
    mysql_free_result($results);
    return $data;
  }

  /**
   * insert()
   *
   * Die Funktion <b>insert()</b> ermoeglicht insert Anfragen an die Datenbank.
   * Dabei werden einfache Fehlerchecks durchgefuert.
   *
   * @param string $sql Eine SQL <b>insert</b> Anfrage an die Datenbank
   * @return int|false Die id des letzten auto_increment Wertes oder <b>false</b> falls ein Fehler auftrat.
   * @see error()
   * @see getLastError()
   * @access public
   *
   */
  function insert($sql = '') {
    if (empty($sql)) {
      return false;
    }
    if (!eregi("^insert",$sql)) {
      return $this->error("MySql->insert called with $sql");
    }
    if (empty($this->conn)) {
      return false;
    }
    $results = mysql_query( $sql, $this->conn );
    if (!$results) {
      return false;
    }
    $results = mysql_insert_id();
    return $results;
  }

  /**
   * Fehler erzeugen / abfragen
   *
   * Die Funktion <b>error()</b> checkt auf MySql Fehler und speichert diese.
   *
   * @param string $text Eine optionale Angabe einer Fehlerursache
   * @return false Es wird immer <b>false</b> zurueck gegeben
   * @see getLastError()
   * @access protected
   *
   */
  function error($text='') {
    $no = mysql_errno();
    $msg = mysql_error();
    $this->errString = "[$text] ( $no : $msg )";
    return false;
  }

  /**
   * Letzten Fehler ueberpruefen
   *
   * Die Funktion <b>getLastError()</b> gibt die letzte mit error
   * gesicherte Fehlermeldung zurueck und loescht diese aus dem Speicher.
   *
   * @return string Die letzte Fehlermeldung
   * @see error()
   * @access public
   *
   */
  function getLastError() {
    $errString = $this->errString;
    $this->errString = '';
    return $errString;
  }

} // end class MySql

?>