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
  
  function ReviewDetailed($id, $paperid, $papertitle, $authoremail, $authorname, $reviewrating, $averagerating, $revieweremail, $reviewername, $summary, $remarks='', $confidential='', $ratings, $comments=''){
    $this->Review($id, $paperid, $papertitle, $authoremail, $authorname, $reviewrating, $averagerating, $revieweremail, $reviewername);
    $this->strSummary = $summary;
    $this->strRemarks = $remarks;
    $this->strConfidential = $confidential;
    $this->intRatings = $ratings;
    $this->strComments = $comments;
  }
  
  function ReviewDetailedFromReview($review, $summary, $remarks='', $confidential='', $ratings, $comments=''){
    $this->ReviewDetailed($review->id, $review->paperid, $review->papertitle, $review->authoremail, $review->authorname, $review->reviewrating, $review->averagerating, $review->revieweremail, $review->reviewername, $summary, $remarks, $confidential, $ratings, $comments);
  }

} // end class ReviewDetailed

?>