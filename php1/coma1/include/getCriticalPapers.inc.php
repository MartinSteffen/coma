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
require_once(INCPATH.'class.papervariance.inc.php');

/**
 * Liefert ein PaperVariance-Array der nach den aktuellen Kriterien als uneindeutig
 * bewerteten Papers zureuck.
 * Benutzt (als Bedingungen):
 * - ConferenceDetailed->fltCriticalVariance
 *
 * @return PaperVariance [] Array von PaperVariance-Objekten; leeres Array, falls
 * kein Paper undeindeutig bewertet wurde
 *
 * @access public
 * @author Falk, Tom (20.01.05)
 */
function getCriticalPapers() {
  $objPapers = array();

  //...
  // Hallo Falk! (Tom hier.) ;-)
  // Habe folgende Idee: Um nicht fuer jeden Scheiss Klassen anzulegen, waere
  // ich dafuer, einfach ein verschachteltes Array zurueckzuliefern. So werde
  // ich es in meinem Algorithmus machen.
  // Etwa so: $x = array();
  // ...
  // $x[] = array('intPaperId' => $intPaperId, 'fltVariance' => $fltVariance);
  // Dann kann man mittels $y = $x[$i] auf $y wie folgt zugreifen:
  // $y['intPaperId'] bzw. $y['fltVariance']. Ist doch irgendwie besser als
  // ne Extra-Klasse, oder? (Nur ein Vorschlag; ich werde es so machen.)

  return $objPapers;
}