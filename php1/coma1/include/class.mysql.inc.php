<?php
/**
 * @version $Id$
 * @package coma1
 */

if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/**
 * simple Klasse zum Zugriff auf die MySQL Datenbank
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @package coma1
 *
 */
class MySql {

  var $mySqlServer = 'localhost';
  var $mySqlUser = '';
  var $mySqlPassword = '';
  var $mySqlDatabase = '';
  var $conn;
  var $errString;

  function MySql() {
    require_once('./config.inc.php');
    $this->mySqlServer = sqlServer;
    $this->mySqlUser = sqlUser;
    $this->mySqlPassword = sqlPassword;
    $this->mySqlDatabase = sqlDatabase;

    // mysql_pconnect ???? Was ist besser? - Jan
    $conn = @mysql_connect(sqlServer, sqlUser , sqlPassword);
    if (!$conn) {
      return $this->error("Could not connect to MySQL: ");
    }

    if (!mysql_select_db(sqlDatabase)) {
      return $this->error("Could not select Database: ");
    }
    $this->conn = $conn;
    return true;
  }

  function select( $sql='' ) {
    if (empty($sql)) {
      return false;
    }
    if (!eregi("^select",$sql)) {
      return $this->error("MySql->select called with $sql");
    }
    if (empty($this->conn)) {
      return false;
    }
    $results = mysql_query( $sql, $this->conn );
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

  function error($text) {
    $no = mysql_errno();
    $msg = mysql_error();
    $this->errString = "[$text] ( $no : $msg )";
    return false;
  }

  function getLastError() {
    $errString = $this->errString;
    $this->errString = '';
    return $errString;
  }

} // End Class

?>