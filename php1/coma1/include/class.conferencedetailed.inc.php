<?php
/**
 * $Id
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

/**
 * Klasse ConferenceDetailed
 *
 * @author  Sandro Esquivel <sae@informatik.uni-kiel.de>
 * @author  Tom Scherzer <tos@informatik.uni-kiel.de>
 * @copyright Copyright (c) 2004, Gruppe: PHP1
 * @package coma1
 * @subpackage Objects
 * @access public
 *
 */
class ConferenceDetailed extends Conference {

  var $strAbstractDeadline; // Abstract Submission Deadline
  var $strPaperDeadline; // Paper Submission Deadline
  var $strReviewDeadline;
  var $strFinalDeadline; // Final Version Deadline
  var $strNotification;
  var $intMinReviewsPerPaper;
  var $intDefaultReviewsPerPaper;
  var $intMinNumberOfPapers;
  var $intMaxNumberOfPapers;
  var $fltCriticalVariance;
  var $blnAutoActivateAccount;
  var $blnAutoOpenPaperForum;
  var $blnAutoAddReviewers;
  var $intNumberOfAutoAddReviewers;
  var $objCriterions;
  var $objTopics;
  
  function ConferenceDetailed($strAbstractDeadline, $strPaperDeadline, $strReviewDeadline,
                              $strFinalDeadline, $strNotification, $intMinReviewsPerPaper,
                              $objCriterions, $objTopics) {
    $this->strAbstractDeadline = $strAbstractDeadline;
    $this->strPaperDeadline = $strPaperDeadline;
    $this->strReviewDeadline = $strReviewDeadline;
    $this->strFinalDeadline = $strFinalDeadline;
    $this->strNotification = $strNotification;
    $this->intMinReviewsPerPaper = $intMinReviewsPerPaper;
    $this->objCriterions = $objCriterions;
    $this->objTopics = $objTopics;
  }

} // end class ConferenceDetailed

?>