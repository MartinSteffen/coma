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
  /** @ignore */
  define('INCPATH', dirname(__FILE__).'/');
}

require_once(INCPATH.'class.errorhandling.inc.php');

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
class MySql extends ErrorHandling {

  /**#@+
   * @access private
   * @var string
   */
  var $mySqlServer = 'localhost';
  var $mySqlUser = '';
  var $mySqlPassword = '';
  var $mySqlDatabase = '';
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
   * stellt die Database ein. Die Daten werden dabei aus der Konfigurations
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
    if (empty($conn)) {
      return $this->error('MySql', "Could not connect to MySQL Server $sqlServer");
    }
    if (!mysql_select_db($sqlDatabase, $conn)) {
      return $this->error('MySql', mysql_error($conn));
    }
    $this->mySqlConnection = $conn;
    return $this->success(true);
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
      return $this->error('delete', 'Empty SQL statement';
    }
    if (!eregi("^delete",$strSql)) {
      return $this->error('delete', "Call with $strSql");
    }
    if (empty($this->mySqlConnection)) {
      return $this->error('delete', 'No database connection');
    }
    $result = mysql_query($strSql, $this->mySqlConnection);
    if (empty($result)) {
      return $this->error('delete', mysql_error($this->mySqlConnection));
    }
    return $this->success();
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
      return $this->error('select', 'Empty SQL statement');
    }
    if (!eregi("^select",$strSql)) {
      return $this->error('select', "Call with $strSql.");
    }
    if (empty($this->mySqlConnection)) {
      return $this->error('select', 'No database connection');
    }
    $results = mysql_query($strSql, $this->mySqlConnection);
    if (empty($results)) {
      $strError = mysql_error($this->mySqlConnection);
      @mysql_free_result($results);
      return $this->error('select', $strError);
    }
    $count = 0;
    $data = array();
    while ($row = mysql_fetch_array($results)) {
      $data[$count] = $row;
      $count++;
    }
    mysql_free_result($results);
    return $this->success($data);
  }

  /**
   * update()
   *
   * Die Funktion <b>update()</b> ermoeglicht update Anfragen an die Datenbank.
   * Dabei werden einfache Fehlerchecks durchgefuert.
   *
   * @return bool <b>true</b> bei Erfolg oder <b>false</b> falls Fehler auftrat.
   * @param string $strSql Eine SQL <b>update</b> Anfrage an die Datenbank
   * @see error()
   * @see getLastError()
   * @access public
   *
   */
  function update($strSql = '') {
    if (empty($strSql)) {
      return $this->error('update', 'Empty SQL statement');
    }
    if (!eregi("^update",$strSql)) {
      return $this->error('update', "Call with $strSql");
    }
    if (empty($this->mySqlConnection)) {
      return $this->error('update', 'No database connection');
    }
    $result = mysql_query($strSql, $this->mySqlConnection);
    if (empty($result)) {
      return $this->error('update', mysql_error($this->mySqlConnection));
    }
    return $this->success($result);
  }
  
  /**
   * insert()
   *
   * Die Funktion <b>insert()</b> ermoeglicht insert Anfragen an die Datenbank.
   * Dabei werden einfache Fehlerchecks durchgefuert.
   *
   * @param string $strSql Eine SQL <b>insert</b> Anfrage an die Datenbank
   * @return int|false Die ID des letzten auto_increment Wertes (0, falls keiner
   *                   erzeugt wird) oder <b>false</b> falls ein Fehler auftrat.
   * @see error()
   * @see getLastError()
   * @access public
   *
   */
  function insert($strSql = '') {
    if (empty($strSql)) {
      return $this->error('insert', 'Empty SQL statement');
    }
    if (!eregi("^insert",$strSql)) {
      return $this->error('insert', "Call with $strSql");
    }
    if (empty($this->mySqlConnection)) {
      return $this->error('insert', 'No database connection');
    }
    $results = mysql_query($strSql, $this->mySqlConnection);
    if (empty($results)) {
      return $this->error('insert', mysql_error());
    }
    return $this->success(mysql_insert_id());
  }

} // end class MySql

?>