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

require_once(INCPATH.'lib.inc.php');
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

function getCriticalPapers(&$myDBAccess, $method = 'variance') {
  $objPapers = array();
  $cid = session('confid');
  $papers = $myDBAccess->getPapersOfConference($cid);
  foreach ($papers as $paper){
    $reviews = $myDBAccess->getReviewsOfPaper($paper->intId);
    if (!empty($reviews)){
      if ($method == 'variance'){
        $avgrating = $reviews[0]->fltAvgRating;
        $val = 0.0;
        foreach ($reviews as $review){
          $val = $val + (($review->fltReviewRating - $avgrating)^2);
        }
        $val = $val / count($reviews);
        $val = ($val - $avgrating)/$val;
        $confdet = $myDBAccess->getConferenceDetailed($cid);
        if ($val > $confdet->fltCriticalVariance){
          $objPapers[] = new PaperVariance($paper->intId, $val);
        }
      }
    }
  }
  return $objPapers;
}
