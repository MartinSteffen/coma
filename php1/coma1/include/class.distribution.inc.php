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
    define('ASSIGNED', 0);
    define('PREFERS', 1); // Topic
    define('WANTS', 2); // Paper
    define('DENIES', 4); // Paper
    define('EXCLUDED', 8); // Paper

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
    $p_id = array(); // enthaelt ID's von Papern
    $p_id_index = array(); // enthaelt Indexposition der ID im Array $p_id
    echo('<br>'.count($data).' Papers found:');
    for ($i = 0; $i < count($data); $i++) {
      $p_id[$i] = $data[$i]['id'];
      echo(' '.$data[$i]['id']);
      $p_id_index[$data[$i]['id']] = $i;
    }

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
    $r_id = array(); // enthaelt ID's von Reviewern
    $r_id_index = array(); // enthaelt ID's von Reviewern
    echo('<br>'.count($data).' Reviewers found:');
    for ($i = 0; $i < count($data); $i++) {
      $r_id[$i] = $data[$i]['id']; // wie bei Papern
      echo(' '.$data[$i]['id']);
      $r_id_index[$data[$i]['id']] = $i; // wie bei Papern
    }

    // Reviewer-Paper-Matrix aufstellen; array_fill ab PHP >= 4.2
    $matrix = array_fill(0, count($r_id), array_fill(0, count($p_id), 0));
    for ($i = 0; $i < count($r_id); $i++) {
      // Bereits zugeteilte Paper
      $s = sprintf("SELECT   d.paper_id AS paper_id".
                   " FROM    Distribution AS d".
                   " INNER   JOIN Paper AS p".
                   " ON      p.id = d.paper_id".
                   " AND     p.conference_id = '%d'".
                   " AND     d.reviewer_id = '%d'",
                   s2db($intConferenceId), s2db($r_id[$i]));
      $assigned = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getDistribution', $this->mySql->getLastError());
      }
      for ($j = 0; $j < count($assigned); $j++) {
        $this->addBit($matrix[$i][$p_id_index[$assigned[$j]['paper_id']]], ASSIGNED);
      }
      // Bevorzugte Themen
/*      $s = sprintf("SELECT   p.id AS paper_id".
                   " FROM    Paper AS p".
                   " INNER   JOIN IsAboutTopic AS iat".
                   " ON      iat.paper_id = p.id".
                   " INNER   JOIN PrefersTopic AS pt".
                   " ON      pt.topic_id = iat.topic_id".
                   " AND     pt.person_id = '%d'".
                   " AND     p.conference_id = '%d'",
                   s2db($r_id[$i]), s2db($intConferenceId));
      $prefers = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getDistribution', $this->mySql->getLastError());
      }
      for ($j = 0; $j < count($prefers); $j++) {
        $this->addBit($matrix[$i][$p_id_index[$prefers[$j]['paper_id']]], PREFERS);
      }
      // Gewuenschte Paper
      $s = sprintf("SELECT   pp.paper_id AS paper_id".
                   " FROM    PrefersPaper AS pp".
                   " INNER   JOIN Paper p".
                   " ON      p.id = pp.paper_id".
                   " AND     pp.person_id = '%d'".
                   " AND     p.conference_id = '%d'",
                   s2db($r_id[$i]), s2db($intConferenceId));
      $wants = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getDistribution', $this->mySql->getLastError());
      }
      for ($j = 0; $j < count($wants); $j++) {
        $this->addBit($matrix[$i][$p_id_index[$wants[$j]['paper_id']]], WANTS);
      }
      // Abgelehnte Paper
      $s = sprintf("SELECT   pp.paper_id AS paper_id".
                   " FROM    DeniesPaper AS pp".
                   " INNER   JOIN Paper p".
                   " ON      p.id = pp.paper_id".
                   " AND     pp.person_id = '%d'".
                   " AND     p.conference_id = '%d'",
                   s2db($r_id[$i]), s2db($intConferenceId));
      $denies = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getDistribution', $this->mySql->getLastError());
      }
      for ($j = 0; $j < count($denies); $j++) {
        $this->addBit($matrix[$i][$p_id_index[$denies[$j]['paper_id']]], DENIES);
      }
      // Ausgeschlossene Paper
      $s = sprintf("SELECT   pp.paper_id AS paper_id".
                   " FROM    ExcludesPaper AS pp".
                   " INNER   JOIN Paper p".
                   " ON      p.id = pp.paper_id".
                   " AND     pp.person_id = '%d'".
                   " AND     p.conference_id = '%d'",
                   s2db($r_id[$i]), s2db($intConferenceId));
      $excluded = $this->mySql->select($s);
      if ($this->mySql->failed()) {
        return $this->error('getDistribution', $this->mySql->getLastError());
      }
      for ($j = 0; $j < count($excluded); $j++) {
        $this->addBit($matrix[$i][$p_id_index[$excluded[$j]['paper_id']]], EXCLUDED);
      }*/
    }
    return $matrix;
  }

  /**
   * @access private
   */
  function getBitArray($int) {
    $a = array();
    for ($i = 0; $i < 32; $i++) {
      $a[] = ($int & (1 << $i)) ? 1 : 0;
    }
    return $a;
  }
  
  /**
   * @access private
   */
  function addBit(&$m, $intBit) {
    $m |= (1 << $intBit);
    return true;
  }
  
  /**
   * @access private
   */
  function switchBit(&$m, $intBit) {
    $m = ~((~(1 << $intBit)) ^ ($m));
    return true;
  }

  /**
   * @access private
   */
  function deleteBit(&$m, $intBit) {
    if ($this->hasBit($intBit)) {
      $this->switchBit($m, $intBit);
    }
    return true;
  }

  /**
   * @access private
   */
  function isBit($m, $intBit) {
    return ($m & (1 << $intBit));
  }
}