<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

if (!defined('INCPATH')) {
  define('INCPATH', dirname(__FILE__).'/');
}

/**
 * Klasse MySql
 *
 * Eine simple Klasse die einfachste Funktionen fuer MySql
 * breitstellt, wie z.B. connect, select, insert;
 * sowie einfache Fehlerbehandlungsroutinen
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
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
  var $strError = '';
  /**#@-*/
  /**
   * Ein Verweiss auf das aktuelle Handle der Datenbank.
   * Sollte nur in Ausnahmefaellen direkt genutzt werden!
   *
   * @access protected
   * @var resource
   */
  var $mySqlConnection;

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
    // ACHTUNG: nicht once! (da nur lokal verfügbar)
    require(INCPATH.'/config.inc.php');
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
    $this->mySqlConnection = $conn;
    return true;
  }

  /**
   * delete()
   *
   * Die Funktion <b>delete()</b> ermoeglicht delete Anfragen an die Datenbank.
   * Dabei werden einfache Fehlerchecks durchgefuert.
   *
   * @param string $strSql Eine SQL <b>delete</b> Anfrage an die Datenbank
   * @return bool <b>true</b> bei Erfolg oder <b>false</b> falls Fehler auftrat.
   * @see error()
   * @see getLastError()
   * @access public
   *
   */
  function delete($strSql='') {
    if (empty($strSql)) {
      return false;
    }
    if (!eregi("^delete",$strSql)) {
      return $this->error("delete called with $strSql");
    }
    if (empty($this->mySqlConnection)) {
      return $this->error('delete: Keine Datenbank-Verbindung');
    }
    $result = mysql_query($strSql, $this->mySqlConnection);
    return $result;
  }

  /**
   * select()
   *
   * Die Funktion <b>select()</b> ermoeglicht select Anfragen an die Datenbank.
   * Dabei werden einfache Fehlerchecks durchgefuert. Das Ergebniss wird
   * automatisch in ein array umgewandelt.
   *
   * @param string $strSql Eine SQL <b>select</b> Anfrage an die Datenbank
   * @return array|false Eine Liste der Ergebnisse oder <b>false</b> falls ein 
   *                     Fehler auftrat.
   * @see error()
   * @see getLastError()
   * @access public
   *
   */
  function select($strSql='') {
    if (empty($strSql)) {
      return false;
    }
    if (!eregi("^select",$strSql)) {
      return $this->error("select called with $strSql");
    }
    if (empty($this->mySqlConnection)) {
      return $this->error('select: Keine Datenbank-Verbindung');
    }
    $results = mysql_query($strSql, $this->mySqlConnection);
    if (empty($results)) {
      @mysql_free_result($results);
      return $this->error('select: ');
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
   * @param string $strSql Eine SQL <b>insert</b> Anfrage an die Datenbank
   * @return bool <b>true</b> bei Erfolg oder <b>false</b> falls Fehler auftrat.
   * @see error()
   * @see getLastError()
   * @access public
   *
   */
  function update($strSql = '') {
    if (empty($strSql)) {
      return false;
    }
    if (!eregi("^update",$strSql)) {
      return $this->error("update called with $strSql");
    }
    if (empty($this->mySqlConnection)) {
      return $this->error('update: Keine Datenbank-Verbindung');
    }
    $result = mysql_query($strSql, $this->mySqlConnection);
    return $result;
  }
  
  /**
   * update()
   *
   * Die Funktion <b>update()</b> ermoeglicht update Anfragen an die Datenbank.
   * Dabei werden einfache Fehlerchecks durchgefuert.
   *
   * @param string $strSql Eine SQL <b>update</b> Anfrage an die Datenbank
   * @return int|false Die id des letzten auto_increment Wertes oder 
   *                   <b>false</b> falls ein Fehler auftrat.
   * @see error()
   * @see getLastError()
   * @access public
   *
   */
  function insert($strSql = '') {
    if (empty($strSql)) {
      return false;
    }
    if (!eregi("^insert",$strSql)) {
      return $this->error("insert called with $strSql");
    }
    if (empty($this->mySqlConnection)) {
      return $this->error('insert: Keine Datenbank-Verbindung');
    }
    $results = mysql_query( $strSql, $this->mySqlConnection );
    if (empty($results)) {
      return $this->error('insert: ');
    }
    return mysql_insert_id();
  }

  /**
   * Fehler erzeugen / abfragen
   *
   * Die Funktion <b>error()</b> checkt auf MySql Fehler und speichert diese.
   *
   * @param string $strError Eine optionale Angabe einer Fehlerursache
   * @return false Es wird immer <b>false</b> zurueck gegeben
   * @see getLastError()
   * @access protected
   *
   */
  function error($strError='') {
    $no = mysql_errno();
    $msg = mysql_error();
    $this->strError = "[MySQL: $strError ( $no : $msg ) ]";
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
    $strError = $this->strError;
    $this->strError = '';
    return $strError;
  }

} // end class MySql

?>