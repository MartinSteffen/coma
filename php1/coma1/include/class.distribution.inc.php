<?php
/**
 * @version $Id:
 * @package coma1
 * @subpackage core
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

if (!defined('INCPATH')) {
  /** @ignore */
  define('INCPATH', dirname(__FILE__).'/');
}

require_once(INCPATH.'header.inc.php');

class Distribution extends ErrorHandling {
  /**#@+
   * @access private
   */
  /**
   * @var MySql
   */
  var $mySql;
  /**#@-*/

  /**
   * Konstruktor: Erzeugt eine Verbindung mit der Datenbank.
   *
   * @param MySql $mySql ein MySql-Objekt
   * @return bool true gdw. erfolgreich
   * @see error()
   * @see getLastError()
   */
  function Distribution(&$mySql) {
    $this->mySql = &$mySql;
    return $this->success();
  }

  /**
   * Liefert ein zweidimensionales Array von Paper/Reviewer-Zuordnungen von den
   * Papern, die noch nicht in der Distribution-Tabelle auftauchen. Um einem
   * bereits vorher zugeordneten Paper weitere Reviewer hinzuzufuegen, siehe
   * andere Funktion.
   *
   * Benutzt (als Bedingungen):
   * - Distribution-Tabelle (Basis)
   *
   * @return int [] Das (ggf. leere) Array mit einem Verteilungsvorschlag bzw. false,
   *                wenn keine gueltige Verteilung existiert oder keine Konferenz
   *                angegeben wird.
   *                Jeder Arrayeintrag enthaelt ein Array mit den Indizes
   *                'intPaperId' und 'intReviewerId'.
   *                Beispiel: $x = getDistribution(); echo($x[$i]['intPaperId']);
   *
   * @access public
   * @author Falk, Tom (20.01.05)
   */
  function getDistribution($intConferenceId) {
    if (empty($intConferenceId)) {
      return $this->success(false);
    }
    $s = sprintf("SELECT   p.id AS paper_id, p.title AS title".
                 " FROM    Paper AS p".
                 " LEFT    JOIN Distribution AS d".
                 " ON      d.paper_id = p.id".
                 " WHERE   p.conference_id = '%s'".
                 " AND     d.paper_id IS NULL",
                 s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getDistribution', $this->mySql->getLastError());
    }
    echo('<br>Unverteilte Paper:<br>');
    for ($i = 0; $i < count($data); $i++) {
      echo('<br>'.$data[$i]['paper_id'].' / '.$data[$i]['title']);
    }
    return false;
  }

}