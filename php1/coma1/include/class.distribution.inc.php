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
   * @return mixed false, falls keine Verteilung gefunden werden konnte bzw.
   *               die Konferenz-ID ungueltig ist; leeres Array, falls keine
   *               weitere Verteilung noetig ist, sonst die Matrix(rev x pap)
   *               mit den hinzuzufuegenden Zuordnungen.
   *
   * @todo Noch ne ganze Menge UND PHPDoc :-)
   */
  function getDistribution($intConferenceId) {
    define('ASSIGNED', -2); // bereits vorher verteilt
    define('SUGGESTED', -1); // Verteilungsvorschlag
/*    define('ASSIGNED', 0);
    define('PREFERS', 1); // Topic
    define('WANTS', 2); // Paper
    define('DENIES', 3); // Paper
    define('EXCLUDED', 4); // Paper*/

    define('NEUTRAL', 10000.0); // Faktor fuer Preferred Topic
    define('PREF', NEUTRAL*1.5); // 1*NEUTRAL < Faktor fuer Preferred Topic < 2*NEUTRAL
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
    // Matrix
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
    $avg_revs = $data[0]['default_reviews_per_paper'];

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
      return $this->success(false);
    }

    echo('<br>'.count($data).' Reviewers found:');
    for ($i = 0; $i < count($data); $i++) {
      $r_id[$i] = $data[$i]['id']; // wie bei Papern
      echo(' '.$data[$i]['id']);
      $r_id_index[$data[$i]['id']] = $i; // wie bei Papern
    }

    // Reviewer-Paper-Matrix aufstellen; array_fill ab PHP >= 4.2
    $matrix = array_fill(0, count($r_id), array_fill(0, count($p_id), NEUTRAL));

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
        //$this->addBit($matrix[$i][$p_id_index[$assigned[$j]['paper_id']]], ASSIGNED);
        $pindex = $p_id_index[$assigned[$j]['paper_id']];
        $matrix[$i][$pindex] = ASSIGNED;
        $p_num_revs[$pindex]++;
        $p_num_revs_total_left[$pindex]--;
        $r_num_papers[$i]++;
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
        if ($matrix[$i][$pindex] > 0) {
          $p_num_revs_total_left[$pindex]--;
          $matrix[$i][$pindex] = 0;
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
        if ($matrix[$i][$pindex] > 0) {
          $matrix[$i][$pindex] = 0;
          $p_num_revs_total_left[$pindex]--;
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
        if ($matrix[$i][$pindex] == 1) {
          //$p_num_revs_pref_left[$pindex]++;
          $matrix[$i][$pindex] = PREF;
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
        if ($matrix[$i][$pindex] == NEUTRAL) {
          //$p_num_revs_pref_left[$pindex]++;
          $matrix[$i][$pindex] = WANT;
        }
      }
    }
    
    // Matrix relativieren: Eintraege von Papers, die viele hohe Eintraege haben, dividieren
    for ($j = 0; $j < count($p_id); $j++) {
      $sum = 0;
      for ($i = 0; $i < count($r_id); $i++) {
        if ($matrix[$i][$j] > 0) {
          $sum += $matrix[$i][$j];
        }
      }
      // dividieren
      $faktor = 0;
      if ($sum > 0) {
        $faktor = count($r_id) / $sum;
      }
      for ($i = 0; $i < count($r_id); $i++) {
        if ($matrix[$i][$j] > 0) {
          $matrix[$i][$j] *= $faktor;
        }
      }
    }

    // Debug: Ausgabe
    for ($i = 0; $i < count($matrix); $i++) {
      echo('<br>Reviewer '.$r_id[$i].':');
      for ($j = 0; $j < count($matrix[$i]); $j++) {
        echo(' '.$matrix[$i][$j]);
      }
    }
    echo('<br>MinRevs: '.$min_revs.' / AvgRevs: '.$avg_revs);
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
    print_r($r_num_papers);
    
    echo('<br><br><br>');

    // Verteilungsschleife
    $blnChanged = true;
    $blnBreak = false;
    while ($blnChanged && !$blnBreak) {
      $blnBreak = true;
      for ($i = 0; $i < count($p_num_revs); $i++) {
        if ($p_num_revs[$i] < $avg_revs) {
          $blnBreak = false;
        }
      }
      if ($blnBreak) {
        break;
      }

      $blnChanged = false;
      // Paper mit den wenigsten Reviewern ermitteln...
      $min = count($r_id)+1; $pindex = -1;
      for ($i = 0; $i < count($p_id); $i++) {
        // nur solche, fuer die noch Reviewer in Frage kommen
        if ($p_num_revs_total_left[$i] > 0 && $p_num_revs[$i] < $min) {
          $min = $p_num_revs[$i];
          $pindex = $i;
        }
      }
      if ($pindex >= 0) {
        // am besten geeigneten Reviewer nehmen
        $max = 0; $rindex = -1;
        for ($i = 0; $i < count($r_id); $i++) {
          if ($matrix[$i][$pindex] > $max) {
            $max = $matrix[$i][$pindex];
            $rindex = $i;
          }
        }
        if ($rindex >= 0) {
          $blnChanged = true;
          //if ($matrix[$rindex][$pindex] > 1) {
          //  $p_num_revs_pref_left[$pindex]--;
          //}
          $p_num_revs_total_left[$pindex]--;
          $p_num_revs[$pindex]++;
          $r_num_papers[$rindex]++;
          $matrix[$rindex][$pindex] = SUGGESTED;
          // Zeile Reviewer "halbieren"
          for ($i = 0; $i < count($p_id); $i++) {
            if ($matrix[$rindex][$i] > 1) {
              $matrix[$rindex][$i] /= 2.0;
              // Reviewer schon ueber dem Schnitt? Dann nochmal reduzieren!
              if ($r_num_papers[$rindex] > $avg_revs && $matrix[$rindex][$i] > 1) {
                $matrix[$rindex][$i] /= 2.0;
              }
              if ($r_num_papers[$rindex] > $avg_revs + 1 && $matrix[$rindex][$i] >= 1) {
                $matrix[$rindex][$i] = 0;
                $p_num_revs_total_left[$i]--;
              }
            }
          }
        }
      }
    }

    // Debug: Ausgabe
    for ($i = 0; $i < count($matrix); $i++) {
      echo('<br>Reviewer '.$r_id[$i].':');
      for ($j = 0; $j < count($matrix[$i]); $j++) {
        echo(' '.$matrix[$i][$j]);
      }
    }
    echo('<br>MinRevs: '.$min_revs.' / AvgRevs: '.$avg_revs);
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
    print_r($r_num_papers);

    /*$text = array(0 => 'assigned', 'prefers', 'wants', 'denies', 'excluded');
    for ($i = 0; $i < count($matrix); $i++) {
      echo('<br>Reviewer '.$r_id[$i].':');
      for ($j = 0; $j < count($matrix[$i]); $j++) {
        for ($k = ASSIGNED; $k <= EXCLUDED; $k++) {
          if ($this->isBit($matrix[$i][$j], $k)) {
            echo(' '.$text[$k].' Paper #'.$p_id[$j]);
          }
        }
      }
    }*/

    // Keine gueltige Verteilung?
    for ($i = 0; $i < count($p_num_revs); $i++) {
      if ($p_num_revs[$i] < $min_revs) {
        return $this->success(false);
      }
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