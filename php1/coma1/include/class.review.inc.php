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
  var $intReviewRating;    // Gesamtbewertung des Papers in diesem Review
                           // (nicht Durchschnitt der Kriterien!)
  var $fltAverageRating;   // Durchschnitt aller Gesamtbewertungen des Papers
  var $strReviewerEmail;
  var $strReviewerName;

  function Review($intId, $intPaperId, $strPaperTitle, $strAuthorEmail, $strAuthorName,
                  $intReviewRating, $fltAverageRating, $strReviewerEmail, $strReviewerName) {
    $this->intId = $intId;
    $this->intPaperId = $intPaperId;
    $this->strPaperTitle = $strPaperTitle;
    $this->strAuthorEmail = $strAuthorEmail;
    $this->strAuthorName = $strAuthorName;
    $this->fltReviewRating = $intReviewRating;
    $this->fltAverageRating = $fltAverageRating;
    $this->strReviewerEmail = $strReviewerEmail;
    $this->strReviewerName = $strReviewerName;
  }

} // end class Review

?>