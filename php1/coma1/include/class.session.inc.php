<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

if (!defined('INCPATH')) {
  /** @ignore */
  define('INCPATH', dirname(__FILE__).'/');
}
require_once(INCPATH.'class.mysql.inc.php');

/*
 * ACHTUNG: aktuellste Version der Tabelle in Spezifikation
 *          hier nur zur schnellen Referenz!
 *
 *
--
-- Tabellenstruktur für Tabelle 'Session'
-- sid normalerweise 128 oder 160
--
CREATE TABLE IF NOT EXISTS Session (
  sid VARCHAR(255) NOT NULL DEFAULT '',
  sname VARCHAR(25) NOT NULL DEFAULT '',
  sdata TEXT,
  stime TIMESTAMP(14) NOT NULL,
  PRIMARY KEY  (sid, sname),
  KEY stime (stime)
) TYPE=MyISAM COMMENT='Session-Verwaltung';
*/

/**
 * Klasse Session
 *
 * Eine einfache Klasse die eine Session-Verwaltung per Datenbank ermoeglicht.
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Session
 * @access public
 *
 */
class Session extends ErrorHandling {

  /**#@+@access private*/
  /**@var MySql*/
  var $mySql;
  /**@var int*/
  var $intMaxLifeTime = 7200;
  /**@var string*/
  var $strSessName = 'sid';
  /**#@-*/

  /**
   * Konstruktor
   *
   * Der Konstrukter stellt die allgemeine Session-Verwaltung ein.
   *
   * @param MySql $mySql Ein MySql Objekt
   * @param string $strSessName Der Name der Session (default: 'sid')
   * @param int $intMaxLifeTime Die Zeit, die die Session gueltig bleibt in
   *                            Sekunden. (default: 7200)
   * @return bool <b>true</b> bei Erfolg <b>false</b> falls ein Fehler auftrat
   * @see error()
   * @see getLastError()
   *
   */
  function Session(&$mySql, $strSessName = 'sid', $intMaxLifeTime = 7200) {
    if (ini_get('session.auto_start') != '0') {
      return $this->error('Init', 'Cannot initialize Sessionmanager (session.auto_start != 0)');
    }
    $this->intMaxLifeTime = $intMaxLifeTime;
    ini_set('session.gc_maxlifetime', $intMaxLifeTime);
    // GC 1% Wahrscheinlichkeit
    ini_set('session.gc_probability', '1');
    ini_set('session.gc_divisor', '100');
    ini_set('session.use_trans_sid','0');

    $this->mySql =& $mySql;

    session_name($strSessName);
    $this->strSessName = $strSessName;
    session_cache_limiter('nocache');
    if (!session_set_save_handler(array(& $this,'sessionOpen'),
                                  array(& $this,'sessionClose'),
                                  array(& $this,'sessionRead'),
                                  array(& $this,'sessionWrite'),
                                  array(& $this,'sessionDestroy'),
                                  array(& $this,'sessionGC'))) {
      return $this->error('Init', 'Cannot initialize save_handler.');
    }
    session_start();
    return $this->success(true);
  }

  /**
  * @param string $strSavePath Session Speicher Pfad (hier unnoetig?!?)
  * @param string $strSessName Der Name der Session (hier 'coma1') (noetig??)
  * @return bool <b>true</b> bei Erfolg, sonst <b>false</b>.
  * @access private
  */
  function sessionOpen($strSavePath, $strSessName) {
    return true;
  }

  /**
  * @return true Die Funktion gibt immer <b>true</b> zurueck.
  * @access private
  */
  function sessionClose() {
    return true;
  }

  /**
  * @param string $strSessId Alphanumerischer Sessions-Name.
  * @return mixed Serialisierter String wenn die Session gefunden wurde, ''
  *               wenn keine Session gefunden wurde, false bei Fehler
  * @access private
  */
  function sessionRead($strSessId) {
    // Check ob Session veraltet!!!
    $sql = "SELECT  sdata ".
          " FROM    Session ".
          " WHERE   sid   = '$strSessId' ".
          " AND     sname = '$this->strSessName' ".
          " AND     UNIX_TIMESTAMP(stime) > (UNIX_TIMESTAMP()-'$this->intMaxLifeTime') ";
    $results = $this->mySql->select($sql);
    if ($this->mySql->failed()) {
      return $this->error('sessionRead', $this->mySql->getLastError());
    }
    if (!$results) {
      $sql = "DELETE ".
            " FROM  Session ".
            " WHERE sid   = '$strSessId' ".
            " AND   sname = '$this->strSessName' ";
      $this->mySql->delete($sql);
      if ($this->mySql->failed()) {
        return $this->error('sessionRead', $this->mySql->getLastError());
      }
      $sql = "INSERT ".
            " INTO  Session ".
            "       (sid,          sname,                sdata, stime) ".
            " VALUES ".
            "       ('$strSessId', '$this->strSessName', NULL,  NOW()) ";
      $this->mySql->insert($sql);
      if ($this->mySql->failed()) {
        return $this->error('sessionRead', $this->mySql->getLastError());
      }
      return $this->success('');
    }
    else {
      return $this->success($results[0]['sdata']);
    }
  }

  /**
  * @param string $strSessId Alphanumerischer Sessions-Name.
  * @param string $strData Serialisierter String mit den zu speichernden Daten.
  * @return bool <b>true</b> bei Erfolg, sonst <b>false</b>.
  * @access private
  */
  function sessionWrite($strSessId, $strData) {
    $sql = "UPDATE  Session ".
          " SET     sdata = '$strData', ".
          "         stime = NOW() ".
          " WHERE   sid   = '$strSessId' ".
          " AND     sname = '$this->strSessName' ";
    $this->mySql->update($sql);
    if ($this->mySql->failed()) {
      return $this->error('sessionWrite', $this->mySql->getLastError());
    }
    return $this->success(true);
  }

  /**
  * @param string $strSessId Alphanumerischer Sessions-Name.
  * @return bool <b>true</b> bei Erfolg, sonst <b>false</b>.
  * @access private
  */
  function sessionDestroy($strSessId) {
    $strSessId = $this->strSessName . $strSessId;
    $sql = "DELETE ".
          " FROM  Session ".
          " WHERE sid   = '$strSessId' ".
          " AND   sname = '$this->strSessName' ";
    $this->mySql->delete($sql);
    if ($this->mySql->failed()) {
      return $this->error('sessionDestroy', $this->mySql->getLastError());
    }
    return $this->success(true);
  }

  /**
  * @param int $intMaxLifeTime Maximale Zeit die eine Session gespeichert wird
  * @return bool <b>true</b> bei Erfolg, sonst <b>false</b>.
  * @access private
  */
  function sessionGC($intMaxLifeTime) {
    $sql = "DELETE ".
          " FROM  Session ".
          " WHERE sname = '$this->strSessName' ".
          " AND   UNIX_TIMESTAMP(stime) < (UNIX_TIMESTAMP()-$intMaxLifeTime) ";
    $this->mySql->delete($sql);
    return true;
  }

} // End Class Session

?>