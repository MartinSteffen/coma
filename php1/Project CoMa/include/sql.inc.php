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

class cMySql {
  
  var mySqlServer;
  var mySqlUser;
  var mySqlPassword;
  var mySqlDatabase;
    
  function cMySql() {
    require_once('./config.inc.php');
    this->mySqlServer = sqlServer;
    this->mySqlUser = sqlUser;
    this->mySqlPassword = sqlPassword;
    this->mySqlDatabase = sqlDatabase;
    
    
    // mysql_connect ???? Was ist besser? - Jan
    mysql_pconnect(sqlServer

  }
  
}



?>