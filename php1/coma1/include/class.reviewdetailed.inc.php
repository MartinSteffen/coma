<?php
/**
 * @version $Id: class.review.inc.php 164 2004-11-25 08:10:39Z waller $
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

require_once('./class.review.inc.php');

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
  
  function ReviewDetailed($intId, $intPaperId, $strPaperTitle, $strAuthorEmail,
                          $strAuthorName, $intReviewRating, $fltAverageRating,
                          $strReviewerEmail, $strReviewerName, $strSummary = '',
                          $strRemarks = '', $strConfidential = '',
                          $intRatings = array(), $strComments = array()) {
    $this->Review($intId, $intPaperId, $strPaperTitle, $strAuthorEmail, $strAuthorName,
                  $intReviewRating, $fltAverageRating, $strReviewerEmail, $strReviewerName);
    $this->strSummary = $strSummary;
    $this->strRemarks = $strRemarks;
    $this->strConfidential = $strConfidential;
    $this->intRatings = $intRatings;
    $this->strComments = $strComments;
  }

} // end class ReviewDetailed

?>