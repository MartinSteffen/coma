<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
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
  // Das Kriterium fuer die Gesamtnote fiel dem Zeitmangel zum Opfer.
  // Konsequenz: Bedeutung von fltReviewRating hat sich komplett geaendert
  // und entspricht jetzt der Bewertung des Papers durch EINEN Reviewer, d.h.
  // der gewichteten Summe der Einzelkriterien.
  // Der Durchschnitt dieser Paperbewertungen verschiedener Reviewer
  // (fltAverageRating) ist dann letztlich entscheidend, ob ein Paper angenommen
  // oder abgelehnt wird.

  var $intId;
  var $intPaperId;
  var $strPaperTitle;
  var $intAuthorId;
  var $strAuthorName;
  var $fltReviewRating;    // Gesamtbewertung des Papers in diesem Review;
                           // ist Durchschnitt der Kriterien! (am 20.01. geaendert!)
  var $fltAverageRating;   // Durchschnitt aller Gesamtbewertungen des Papers
  var $strReviewerEmail;
  var $strReviewerName;

  function Review($intId, $intPaperId, $strPaperTitle, $intAuthorId, $strAuthorName,
                  $fltReviewRating, $fltAverageRating, $strReviewerEmail, $strReviewerName) {
    $this->intId = $intId;
    $this->intPaperId = $intPaperId;
    $this->strPaperTitle = $strPaperTitle;
    $this->intAuthorId = $intAuthorId;
    $this->strAuthorName = $strAuthorName;
    $this->fltReviewRating = $fltReviewRating;
    $this->fltAverageRating = $fltAverageRating;
    $this->strReviewerEmail = $strReviewerEmail;
    $this->strReviewerName = $strReviewerName;
  }

} // end class Review

?>