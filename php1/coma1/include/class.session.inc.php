<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/* Session table structure:
--
-- Tabellenstruktur für Tabelle `Sessions`
--
CREATE TABLE IF NOT EXISTS `Sessions` (
  `sid` varchar(255) NOT NULL default '',
  `sname` varchar(255) NOT NULL default '',
  `sdata` text,
  `stime` timestamp(14) NOT NULL,
  PRIMARY KEY  (`sid`),
  KEY `stime` (`stime`),
  KEY `sname` (`sname`)
) TYPE=MyISAM COMMENT='Session Verwaltung';
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
class Session {

  /**#@+@access private*/
  /**@var MySql*/
  var $mySql;
  /**@var string*/
  var $strError = '';
  /**@var string*/
  var $strSessName = '';
  /**#@-*/

  /**
   * Konstruktor
   *
   * Der Konstrukter stellt die allgemeine Session-Verwaltung ein.
   *
   * @param MySql $mySql Ein MySql Objekt
   * @return bool <b>true</b> bei Erfolg <b>false</b> falls ein Fehler auftrat
   * @see error()
   * @see getLastError()
   *
   */
  function Session(&$mySql) {
    $this->mySql =& $mySql;

    session_name('coma1');
    session_cache_limiter('nocache');
    if (!session_set_save_handler(array(& $this,'sessionOpen'),
                                  array(& $this,'sessionClose'),
                                  array(& $this,'sessionRead'),
                                  array(& $this,'sessionWrite'),
                                  array(& $this,'sessionDestroy'),
                                  array(& $this,'sessionGC'))) {
      return $this->error('Konnte Sessionmanger nicht initialisieren (save_handler).');
    }
    session_start();
    return true;
  }

  /**
  * @param string $strSavePath Session Speicher Pfad (hier unnoetig?!?)
  * @param string $strSessName Der Name der Session (hier 'coma1') (noetig??)
  * @return bool <b>true</b> bei Erfolg, sonst <b>false</b>.
  * @access private
  */
  function sessionOpen($strSavePath, $strSessName) {
    $this->strSessName = $strSessName;
    // @TODO Was muessen wir hier noch machen?
    return true;
  }

  /**
  * @retrun true Die Funktion gibt immer <b>true</b> zurueck.
  * @access private
  */
  function sessionClose() {
    // @TODO Muessen wir was aufrauemen?
    return true;
  }

  /**
  * @param string $strSessId Alphanumerischer Sessions-Name.
  * @return mixed Serialisierter String wenn die Session gefunden wurde, '' wenn keine Session gefunden wurde, false bei Fehler
  * @access private
  */
  function sessionRead($strSessId) {
    $strSessId = $this->strSessName . $strSessId;
    $results = $this->mySql->select("SELECT sdata FROM Sessions WHERE sid='$strSessId' AND sname='$this->strSessName'");
    if (!$results) {
      $this->mySql->delete("DELETE FROM Sessions WHERE sid='$strSessId' AND sname='$this->strSessName'");
      $this->mySql->insert("INSERT INTO Sessions (sid, sname, sdata, stime) VALUES ('$strSessId', '$this->strSessName', NULL, NOW())");
      $s = $this->mySql->getLastError();
      if (!empty($s)) {
        return $this->error('Fehler beim Schreiben der Session. '.$s);
      }
      return '';
    }
    else {
      return $results[0]['sdata'];
    }
  }

  /**
  * @param string $strSessId Alphanumerischer Sessions-Name.
  * @param string $strData Serialisierter String mit den zu speichernden Daten.
  * @return bool <b>true</b> bei Erfolg, sonst <b>false</b>.
  * @access private
  */
  function sessionWrite($strSessId, $strData) {
    $strSessId = $this->strSessName . $strSessId;
    $this->mySql->update("UPDATE Sessions SET sdata='$strData', stime=NOW() WHERE sid='$strSessId' AND sname='$this->strSessName'");
    return true;
  }

  /**
  * @param string $strSessId Alphanumerischer Sessions-Name.
  * @return bool <b>true</b> bei Erfolg, sonst <b>false</b>.
  * @access private
  */
  function sessionDestroy($strSessId) {
    $strSessId = $this->strSessName . $strSessId;
    $this->mySql->delete("DELETE FROM Sessions WHERE sid='$strSessId' AND sname='$this->strSessName'");
    return true;
  }

  /**
  * @param int $intMaxLifetime Maximale Zeit die eine Session gespeichert werden soll
  * @return bool <b>true</b> bei Erfolg, sonst <b>false</b>.
  * @access private
  */
  function sessionGC($intMaxLifetime) {
    $this->mySql->delete("DELETE FROM Sessions WHERE stime>(UNIX_TIMESTAMP(NOW())-$intMaxLifetime) AND sname='$this->strSessName'");
    return true;
  }

  /**
   * Fehler erzeugen
   *
   * Die Funktion <b>error()</b> erzeugt und speichert einen Fehlerstring.
   *
   * @param string $strError Optionale Angabe einer Fehlerursache
   * @return false Es wird immer <b>false</b> zurueck gegeben
   * @see getLastError()
   * @access protected
   *
   */
  function error($strError='') {
    $this->strError = "[Sessions: $strError ]";
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

} // End Class Session

?>