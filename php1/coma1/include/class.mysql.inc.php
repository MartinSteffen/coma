<?php
/**
 * simple Klasse zum Zugriff auf die MySQL Datenbank
 *
 * $Id$
 *
 */

if ( !defined('IN_COMA1') )
{
  exit('Hacking attempt');
}

class MySql {

  var $mySqlServer;
  var $mySqlUser;
  var $mySqlPassword;
  var $mySqlDatabase;
  var $myConn;

  function MySql()
  {
    require_once('./config.inc.php');
    $this->mySqlServer = sqlServer;
    $this->mySqlUser = sqlUser;
    $this->mySqlPassword = sqlPassword;
    $this->mySqlDatabase = sqlDatabase;

    // mysql_pconnect ???? Was ist besser? - Jan
    $conn = @mysql_connect(sqlServer, sqlUser , sqlPassword);
    if (!$conn)
    {
      $this->error("cMySql Connection Error");
    }

    if ( !mysql_select_db($this->mySqlDatabase) )
    {
      $this->error("Database Error");
    }
    $this->myConn = $conn;
    return true;
  }

  function error($text)
  {
    $no = mysql_errno();
    $msg = mysql_error();
    echo "[$text] ( $no : $msg )<BR>\n";
    exit(-1);
  }

  function select( $sql='' )
  {
    if ( empty($sql) )
    { 
      return false;
    }
    if ( !eregi("^select",$sql) )
    {
      if (defined('DEBUG')) echo "<H2 >cMySql->select called with $sql</H2>\n";
      return false;
    }
    if ( empty($this->myConn) )
    {
      return false;
    }
    $results = mysql_query( $sql, $this->myConn );
    if ( (!$results) or (empty($results)) )
    {
      @mysql_free_result($results);
      return false;
    }
    $count = 0;
    $data = array();
    while ( $row = mysql_fetch_array($results)) {
      $data[$count] = $row;
      $count++;
    }
    mysql_free_result($results);
    return $data;
  }

  function insert( $sql='' )
  {
    if ( empty($sql) )
    { 
      return false;
    }
    if ( !eregi("^insert",$sql) )
    {
      if (defined('DEBUG')) echo "<H2 >cMySql->insert called with $sql</H2>\n";
      return false;
    }
    if ( empty($this->myConn) )
    {
      return false;
    }
    $results = mysql_query( $sql, $this->myConn );
    if ( !$results )
    {
      return false;
    }
    $results = mysql_insert_id();
    return $results;
  }

} // End Class

?>