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
  `sdata` text,
  `stime` timestamp(14) NOT NULL,
  PRIMARY KEY  (`sid`),
  KEY `stime` (`stime`)
) TYPE=MyISAM COMMENT='Session Verwaltung';
*/

// Konstante fuer den Namen der Session
$SESSIONNAME = 'coma1';

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
  function Session($mySql) {
    $this->mySql = $mySql;

    session_name('$SESSIONNAME');
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
  * @param string $save_path Session Speicher Pfad (hier unnoetig?!?)
  * @param string $sess_name Der Name der Session (hier $SESSIONNAME) (noetig??)
  * @return bool <b>true</b> bei Erfolg, sonst <b>false</b>.
  * @access private
  */
  function sessionOpen($save_path, $sess_name) {
    // @TODO Was muessen wir hier machen?
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
  * @param string $sess_id Alphanumerischer Sessions-Name.
  * @return mixed Serialisierter String wenn die Session gefunden wurde, '' wenn keien Session gefunden wurde, false bei Fehler
  * @access private
  */
  function sessionRead($sess_id) {
    $results = $this->mySql->select("SELECT sdata FROM Sessions WHERE sid='$sess_id'");
    if (!$results) {
      $this->mySql->delete("DELETE FROM Sessions WHERE sid='$sess_id'");
      $this->mySql->insert("INSERT INTO Sessions (sid, sdata, stime) VALUES ('$sess_id', NULL, NOW())");
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
  * @access private
  */
  function sessionWrite($sess_id, $val) {
  }

  /**
  * @access private
  */
  function sessionDestroy($sess_id) {
  }

  /**
  * @access private
  */
  function sessionGC($max_lifetime) {
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