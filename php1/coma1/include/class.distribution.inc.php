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
require_once(INCPATH.'class.conferencedetailed.inc.php');

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
    // Paper-ID's holen
    $s = sprintf("SELECT id FROM Paper WHERE conference_id = '%d' ORDER BY id ASC",
                 s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getDistribution', $this->mySql->getLastError());
    }
    if (empty($data)) {
      return array();
    }
    // Paper-Indizierungsarray erstellen
    $p_pos_id = array(); // enthaelt ID's von Papern
    $p_id_pos = array(); // enthaelt Position der ID im Array $p_pos_id
    for ($i = 0; $i < count($data); $i++) {
      $p_pos_id[$i] = $data[$i]['id'];
      $p_id_pos[$data[$i]['id']] = $i;
    }
    echo('<br>'.count($data).' Papers found.');
    // Reviewer-ID's holen
    $s = sprintf("SELECT   p.id".
                 " FROM    Person AS p".
                 " INNER   JOIN Role AS r".
                 " ON      r.person_id = p.id".
                 " AND     r.role_type = '%d'".
                 " WHERE   r.conference_id = '%d'".
                 " ORDER   BY p.id ASC",
                 s2db(REVIEWER), s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getDistribution', $this->mySql->getLastError());
    }
    if (empty($data)) {
      return array();
    }
    // Reviewer-Indizierungsarray erstellen
    $r_pos_id = array(); // enthaelt ID's von Reviewern
    for ($i = 0; $i < count($data); $i++) {
      $r_pos_id[$i] = $data[$i]['id']; // wie bei Papern
      $r_id_pos[$data[$i]['id']] = $i; // wie bei Papern
    }
    echo('<br>'.count($data).' Reviewers found.');
    // Reviewer-Paper-Matrix aufstellen; array_fill ab PHP >= 4.2
    $matrix = array_fill(0, count($r_pos_id)-1, array_fill(0, count($p_pos_id)-1, 0));
    // Bereits zugeteilte Paper in die Matrix eintragen
    /*for ($i = 0; $i < count($r_pos_id); $i++) {
      $s = sprintf("SELECT paper_id FROM Distribution WHERE reviewer_id = '%d'",
                   s2db($r_id[$i]));
      $data = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getDistribution', $this->mySql->getLastError());
      }
      for ($j = 0; $j < count($data); $j++) {
        
      }
    }*/
    for ($i = 0; $i < count($p_pos_id); $i++) {
      echo ('<br>'.$i.': '.$p_pos_id[$i].'; ');
      echo ($p_id_pos[$p_pos_id[$i]]);
    }
    return $matrix;
  }

}