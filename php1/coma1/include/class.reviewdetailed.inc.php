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
require_once(INCPATH.'./class.review.inc.php');

/**
 * Klasse ReviewDetailed
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @author  Falk Starke <fast@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class ReviewDetailed extends Review {

  var $strSummary;       // Zusammenfassung des Reviewers unter seinen Report
  var $strRemarks;       // Bemerkungen des Reviewers unter seinen Report
  var $strConfidential;  // Notiz des Chairs zu einem Reviewreport
  var $intRatings;       // Punkte in den einzelnen Kriterien
  var $strComments;      // Kommentare zu den einzelnen Kriterien
  var $objCriterions;    // die einzelnen Kriterien selbst

  function ReviewDetailed($intId, $intPaperId, $strPaperTitle, $intAuthorId,
                          $strAuthorName, $fltReviewRating, $fltAverageRating,
                          $strReviewerEmail, $strReviewerName, $strSummary,
                          $strRemarks, $strConfidential, $intRatings, $strComments,
                          $objCriterions) {
    $this->Review($intId, $intPaperId, $strPaperTitle, $intAuthorId, $strAuthorName,
                  $fltReviewRating, $fltAverageRating, $strReviewerEmail, $strReviewerName);
    $this->strSummary = $strSummary;
    $this->strRemarks = $strRemarks;
    $this->strConfidential = $strConfidential;
    $this->intRatings = $intRatings;
    $this->strComments = $strComments;
    $this->objCriterions = $objCriterions;
  }

} // end class ReviewDetailed

?>