<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
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

  var $strSummary;
  var $strRemarks;
  var $strConfidential;
  var $intRatings;
  var $strComments;
  var $objCriterions;

  function ReviewDetailed($intId, $intPaperId, $strPaperTitle, $strAuthorEmail,
                          $strAuthorName, $intReviewRating, $fltAverageRating,
                          $strReviewerEmail, $strReviewerName, $strSummary,
                          $strRemarks, $strConfidential, $intRatings, $strComments,
                          $objCriterions) {
    $this->Review($intId, $intPaperId, $strPaperTitle, $strAuthorEmail, $strAuthorName,
                  $intReviewRating, $fltAverageRating, $strReviewerEmail, $strReviewerName);
    $this->strSummary = $strSummary;
    $this->strRemarks = $strRemarks;
    $this->strConfidential = $strConfidential;
    $this->intRatings = $intRatings;
    $this->strComments = $strComments;
    $this->objCriterions = $objCriterions;
  }

} // end class ReviewDetailed

?>