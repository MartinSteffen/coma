<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/**
 * Klasse Review
 *
 * @author  Jan Waller <jwa@informatik.uni-kiel.de>
 * @author  Falk Starke <fast@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class Review {

  var $intId;
  var $intPaperId;
  var $strPaperTitle;
  var $strAuthorEmail;
  var $strAuthorName;
  var $intReviewRating;
  var $intAverageRating;
  var $strReviewerEmail;
  var $strReviewerName;
  
  function Review($id, $paperid, $papertitle, $authoremail, $authorname, $reviewrating, $averagerating, $revieweremail, $reviewername){
    $this->intId = $id;
    $this->intPaperId = $paperid;
    $this->strPaperTitle = $papertitle;
    $this->strAuthorEmail = $authoremail;
    $this->strAuthorName = $authorname;
    $this->intReviewRating = $reviewrating;
    $this->intAverageRating = $averagerating;
    $this->strReviewerEmail = $revieweremail;
    $this->strReviewerName = $reviewername;
  }

} // end class Review

?>