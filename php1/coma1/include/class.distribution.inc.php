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


// Globale Konstanten (nach aussen; auch im CSS benutzt!)
define('ASSIGNED', 0); // Paper
define('SUGGESTED', 1); // Paper
define('NEUTRAL', 2); // Topic
define('PREFERS', 3); // Topic
define('WANTS', 4); // Paper
define('DENIES', 5); // Paper
define('EXCLUDED', 6); // Paper


/**
 * Klasse Distribution
 *
 * @author Tom Scherzer <tos@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage core
 * @access public
 *
 */
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
   * @param int $intConferenceId Konferenz-ID
   * @param int $intWantedReviewers Optional: Assoziatives Array mit Paper-ID's
   *            als Schluessel und Anzahl an gewuenschten Reviewers als Wert.
   *            Ist z.B. der gewuenschte Durchschnittswert an Reviewern pro
   *            Paper 3 und soll beispielsweise Paper #7 von 6 und Paper #12
   *            von 5 Reviewern reviewed werden, waere $intWantedReviewers =
   *            array(7 => 6, 12 => 5) zu uebergeben.
   * @return mixed false, falls keine Verteilung gefunden werden konnte bzw.
   *               die Konferenz-ID ungueltig ist; leeres Array, falls keine
   *               weitere Verteilung noetig ist, sonst die Matrix(rev x pap)
   *               mit den hinzuzufuegenden Zuordnungen.
   *
   * @todo Noch ne ganze Menge UND PHPDoc :-)
   * @todo $color entfernen! $p_num_revs_pref_left kann raus
   * @todo zusaetzliche Reviewer suchen und verteilen
   * @todo Vorschlaege fuer einzelne Paper: Reviewer, die am geeignetsten sind
   *       (z.B. 5 Stueck) und die, die am wenigsten zu tun haben...
   */
  function getDistribution($intConferenceId, $intWantedReviewers = array()) {
    define('ASSI', -2); // bereits vorher verteilt
    define('SUGG', -1); // Verteilungsvorschlag
    define('NEUT', 10000.0); // Faktor fuer Preferred Topic
    define('PREF', NEUT*1.5); // 1*NEUT < Faktor fuer Preferred Topic < 2*NEUT
    define('WANT', PREF*1.5); // 1*PREF < Faktor fuer Preferred Paper < 2*PREF

    if (empty($intConferenceId)) {
      return $this->success(false);
    }

    // Paper-Indizierungsarray
    $p_id = array(); // Zuordnung [0..n-1] -> [PapID1, ... PapIDn]
    $p_id_index = array(); // Zuordnung [PapID1, ... PapIDn] -> [0..n-1]
    // Reviewer-Indizierungsarray
    $r_id = array(); // Zuordnung [0..n-1] -> [RevID1, ... RevIDn]
    $r_id_index = array(); // Zuordnung [RevID1, ... RevIDn] -> [0..n-1]
    // Anzahl moeglicher Reviewer fuer das Paper unter Beruecksichtigung der Wuensche
    //$p_num_revs_pref_left = array();
    // Anzahl moeglicher Reviewer fuer das Paper insgesamt
    $p_num_revs_total_left = array();
    // Verteilte Reviewer fuer Paper
    $p_num_revs = array();
    // Verteilte Paper fuer Reviewer
    $r_num_papers = array();
    // Durchschnittliche Anzahl von Reviewern pro Paper (falls moeglich)
    $avg_revs_per_paper = false;
    // Maximale Anzahl von Papern pro Reviewer
    //$max_papers_per_rev = false;
    // Matrix initial mit bisheriger Verteilung, Wuenschen usw.
    $initial_matrix = array();
    // Vorschlagsmatrix
    $matrix = array();

    // Konfigurationsdaten holen
    $s = sprintf("SELECT min_reviews_per_paper FROM Conference WHERE id = '%d'",
                 s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getDistribution', $this->mySql->getLastError());
    }
    if (empty($data)) {
      return $this->error('getDistribution', 'Fatal error: Database inconsistency!',
                          "intConferenceId = $intConferenceId");
    }
    $min_revs = $data[0]['min_reviews_per_paper'];

    $s = sprintf("SELECT default_reviews_per_paper FROM ConferenceConfig WHERE id = '%d'",
                 s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getDistribution', $this->mySql->getLastError());
    }
    if (empty($data)) {
      return $this->error('getDistribution', 'Fatal error: Database inconsistency!',
                          "intConferenceId = $intConferenceId");
    }
    $avg_revs_per_paper = $data[0]['default_reviews_per_paper'];
    //$max_papers_per_rev = $data[0]['???'];

    // Paper-ID's holen
    $s = sprintf("SELECT id FROM Paper WHERE conference_id = '%d' ORDER BY id ASC",
                 s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getDistribution', $this->mySql->getLastError());
    }
    if (empty($data)) {
      return $this->success(array());
    }

    //echo('<br>'.count($data).' Papers found:');
    for ($i = 0; $i < count($data); $i++) {
      $p_id[$i] = $data[$i]['id'];
      //echo(' '.$data[$i]['id']);
      $p_id_index[$data[$i]['id']] = $i;
    }

    // Reviewer-ID's holen
    $s = sprintf("SELECT   p.id".
                 " FROM    Person AS p".
                 " INNER   JOIN Role AS r".
                 " ON      r.person_id = p.id".
                 " AND     r.role_type = '%d'".
                 " AND     r.state IS NULL".
                 " WHERE   r.conference_id = '%d'".
                 " ORDER   BY p.id ASC",
                 s2db(REVIEWER), s2db($intConferenceId));
    $data = $this->mySql->select($s);
    if ($this->mySql->failed()) {
      return $this->error('getDistribution', $this->mySql->getLastError());
    }
    if (empty($data)) {
      return $this->success(false);
    }

    //echo('<br>'.count($data).' Reviewers found:');
    for ($i = 0; $i < count($data); $i++) {
      $r_id[$i] = $data[$i]['id']; // wie bei Papern
      //echo(' '.$data[$i]['id']);
      $r_id_index[$data[$i]['id']] = $i; // wie bei Papern
    }

    // Reviewer-Paper-Matrix aufstellen; array_fill ab PHP >= 4.2
    $initial_matrix = array_fill(0, count($r_id), array_fill(0, count($p_id), NEUTRAL));
    $matrix = array_fill(0, count($r_id), array_fill(0, count($p_id), NEUT));
    $color = array_fill(0, count($r_id), array_fill(0, count($p_id), 'FFFFFF'));

    //$p_num_revs_pref_left = array_fill(0, count($p_id), 0);
    $p_num_revs_total_left = array_fill(0, count($p_id), count($r_id));
    $p_num_revs = array_fill(0, count($p_id), 0);
    $r_num_papers = array_fill(0, count($r_id), 0);
    
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
        //$this->addBit($matrix[$i][$p_id_index[$assigned[$j]['paper_id']]], ASSI);
        $pindex = $p_id_index[$assigned[$j]['paper_id']];
        $initial_matrix[$i][$pindex] = ASSIGNED;
        $matrix[$i][$pindex] = ASSI;
        $p_num_revs[$pindex]++;
        $p_num_revs_total_left[$pindex]--;
        $r_num_papers[$i]++;
        $color[$i][$pindex] = '999999';
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
        //$this->addBit($matrix[$i][$p_id_index[$excluded[$j]['paper_id']]], EXCLUDED);
        $pindex = $p_id_index[$excluded[$j]['paper_id']];
        $initial_matrix[$i][$pindex] =
          ($initial_matrix[$i][$pindex]==NEUTRAL?EXCLUDED:$initial_matrix[$i][$pindex]);
        if ($matrix[$i][$pindex] > 0) {
          $p_num_revs_total_left[$pindex]--;
          $matrix[$i][$pindex] = 0;
          $color[$i][$pindex] = '990000';
        }
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
        //$this->addBit($matrix[$i][$p_id_index[$denies[$j]['paper_id']]], DENIES);
        $pindex = $p_id_index[$denies[$j]['paper_id']];
        $initial_matrix[$i][$pindex] =
          ($initial_matrix[$i][$pindex]==NEUTRAL?DENIES:$initial_matrix[$i][$pindex]);
        if ($matrix[$i][$pindex] > 0) {
          $matrix[$i][$pindex] = 0;
          $p_num_revs_total_left[$pindex]--;
          $color[$i][$pindex] = 'FF00000';
        }
      }
      // Bevorzugte Themen
      $s = sprintf("SELECT   p.id AS paper_id".
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
        //$this->addBit($matrix[$i][$p_id_index[$prefers[$j]['paper_id']]], PREFERS);
        $pindex = $p_id_index[$prefers[$j]['paper_id']];
        $initial_matrix[$i][$pindex] =
          ($initial_matrix[$i][$pindex]==NEUTRAL?PREFERS:$initial_matrix[$i][$pindex]);
        if ($matrix[$i][$pindex] == NEUT) {
          //$p_num_revs_pref_left[$pindex]++;
          $matrix[$i][$pindex] = PREF;
          $color[$i][$pindex] = '009900';
        }
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
        //$this->addBit($matrix[$i][$p_id_index[$wants[$j]['paper_id']]], WANTS);
        $pindex = $p_id_index[$wants[$j]['paper_id']];
        $initial_matrix[$i][$pindex] =
          ($initial_matrix[$i][$pindex]==NEUTRAL||$initial_matrix[$i][$pindex]==PREFERS?
          WANTS:$initial_matrix[$i][$pindex]);
        if ($matrix[$i][$pindex] >= NEUT) {
          //$p_num_revs_pref_left[$pindex]++;
          $matrix[$i][$pindex] = WANT;
          $color[$i][$pindex] = '00FF00';
        }
      }
    }

    //echo('<br><br><br>');

    // $intWantedReviewers mit Average-Werten belegen, falls nicht explizit
    // ein anderer Wert gesetzt wurde.
    for ($i = 0; $i < count($p_id); $i++) {
      if (!isset($intWantedReviewers[$p_id[$i]]) || $intWantedReviewers[$p_id[$i]] <= 0) {
        $intWantedReviewers[$p_id[$i]] = $avg_revs_per_paper;
      } 
      echo('<br>Wanted '.$p_id[$i].': '.$intWantedReviewers[$p_id[$i]]);
    }

    // Paper-Wuensche zuerst beruecksichtigen
    for ($i = 0; $i < count($r_id); $i++) {
      $tmp = array();
      for ($j = 0; $j < count($p_id); $j++) {
        if ($matrix[$i][$j] == WANT) {
          $tmp[] = $j;
        }
      }
      for ($j = 0; $j < count($tmp); $j++) {
        $this->suggest($matrix, $i, $tmp[$j], $p_id, count($p_id),
                       $p_num_revs_total_left, $p_num_revs, $r_num_papers, SUGG);
      }
    }

    // Verteilungsschleife
    $blnChanged = true;
    $blnBreak = false;
    while ($blnChanged && !$blnBreak) {
      $blnBreak = true;
      for ($i = 0; $i < count($p_num_revs); $i++) {
        if ($p_num_revs[$i] < $intWantedReviewers[$p_id[$i]]) {
          $blnBreak = false;
        }
      }
      if ($blnBreak) {
        break;
      }

      $blnChanged = false;

      // Paper mit dem niedrigsten Faktor n/m (n=Anzahl Reviewer, m=gewunschte Reviewer)
      // ermitteln... (Paper mit hoeherem m bei gleichem Faktor bevorzugen.)
      $minFactor = 1; $wanted = -1; $pindex = -1;
      for ($i = 0; $i < count($p_id); $i++) {
        // nur solche, fuer die noch Reviewer in Frage kommen
        if ($p_num_revs_total_left[$i] > 0) {
          if($p_num_revs[$i] / $intWantedReviewers[$p_id[$i]] < $minFactor ||
              (abs($p_num_revs[$i] / $intWantedReviewers[$p_id[$i]] - $minFactor) <= 0.005 &&
               $intWantedReviewers[$p_id[$i]] > $wanted)) {
            $minFactor = $p_num_revs[$i] / $intWantedReviewers[$p_id[$i]];
            $wanted = $intWantedReviewers[$p_id[$i]];
            $pindex = $i;
          }
        }
      }
      if ($pindex >= 0) {
        // geeignetsten Reviewer nehmen
        $max = 0; $rindex = -1;
        for ($i = 0; $i < count($r_id); $i++) {
          if ($matrix[$i][$pindex] > $max) {
          $max = $matrix[$i][$pindex];
            $rindex = $i;
          }
        }
      }

      if ($rindex >= 0 && $pindex >= 0) {
        $blnChanged = true;
        $this->suggest($matrix, $rindex, $pindex, $p_id, count($p_id),
                       $p_num_revs_total_left, $p_num_revs, $r_num_papers, SUGG);
      }
    }
    
    // Korrekturen: Reviewer mit neutralem Paper gegen gewuenschtes tauschen,
    // wenn es dem Partner egal ist
    // (...)

    // Debug: Ausgabe
    /*for ($i = 0; $i < count($matrix); $i++) {
      echo('<br>Reviewer '.$r_id[$i].':');
      for ($j = 0; $j < count($matrix[$i]); $j++) {
        echo(' '.$matrix[$i][$j]);
      }
    }*/
    
    echo('<table>');
    for ($i = 0; $i < count($matrix); $i++) {
      echo('<tr><td>Reviewer '.$r_id[$i].'</td>');
      for ($j = 0; $j < count($matrix[$i]); $j++) {
        if ($matrix[$i][$j] == SUGG && $color[$i][$j] == 'FFFFFF') {
          $color[$i][$j] = 'FFFF00';
        }
        echo('<td bgcolor='.$color[$i][$j].'>'.$matrix[$i][$j].'</td>');
      }
      echo('</tr>');
    }
    echo('</table>');
    
    /*echo('<br>MinRevs: '.$min_revs.' / AvgRevs: '.$avg_revs_per_paper);
    echo('<br>p_id_index:');
    print_r($p_id_index);
    echo('<br>r_id_index:');
    print_r($r_id_index);
    //echo('<br>NumRevsPrefLeft:');
    //print_r($p_num_revs_pref_left);
    echo('<br>NumRevsTotalLeft:');
    print_r($p_num_revs_total_left);
    echo('<br>NumRevs:');
    print_r($p_num_revs);
    echo('<br>NumPapers:');
    print_r($r_num_papers);*/

    // Keine gueltige Verteilung?
    for ($i = 0; $i < count($p_num_revs); $i++) {
      if ($p_num_revs[$i] < $min_revs) {
        return $this->success(false);
      }
    }
    
    // Ausgabearray aufbereiten:
    // Laenge = Anzahl Papers (mit Indizes der DB-Paper-ID)
    // Pro Zeile: Ein Reviewer-Array 'reviewers', welches wiederum pro Zeile ein
    // Array mit folgenden Eintraegen enthaelt:
    // 'reviewer_id' und 'status' (enthaelt den Wert einer globalen Konstanten)
    $y = array();
    for ($j = 0; $j < count($p_id); $j++) {
      $r = array();
      for ($i = 0; $i < count($r_id); $i++) {
        if ($matrix[$i][$j] == ASSI || $matrix[$i][$j] == SUGG) {
          $r[] = array('reviewer_id' => $r_id[$i], 'status' => $initial_matrix[$i][$j]);
        }
      }
      $y[$p_id[$j]] = $r;
    }

    return $y;
  }

  /**
   * @access private
   */
  function suggest(&$matrix, $rindex, $pindex, $p_id, $max_revs_per_rev, &$p_num_revs_total_left,
                   &$p_num_revs, &$r_num_papers, $intSuggested) {
    $p_num_revs_total_left[$pindex]--;
    $p_num_revs[$pindex]++;
    $r_num_papers[$rindex]++;
    $matrix[$rindex][$pindex] = $intSuggested;
    for ($i = 0; $i < count($p_id); $i++) {
      if ($matrix[$rindex][$i] > 1) {
        $matrix[$rindex][$i] /= 2.0;
        if ($r_num_papers[$rindex] >= $max_revs_per_rev && $matrix[$rindex][$i] >= 1) {
          $matrix[$rindex][$i] = 0;
          $p_num_revs_total_left[$i]--;
        }
      }
    }
    return true;
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