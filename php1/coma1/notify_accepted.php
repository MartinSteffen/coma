<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
require_once('./include/header.inc.php');

// Pruefe Zugriffsberechtigung auf die Seite
checkAccess(CHAIR);

$mainIfArray = array(1);
$strMessage = '';

$objChair = $myDBAccess->getPerson(session('uid'));
if ($myDBAccess->failed()) {
  error('get chair data', $myDBAccess->getLastError());
}
else if (empty($objChair)) {
  error('get chair data', 'Chair does not exist in database!');	
}
$strFrom = '"'.$objChair->getName(2).'" <'.$objChair->strEmail.'>';
$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get conference data', $myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('get conference data', 'Conference does not exist in database!');
}
$strMailAssocs = defaultAssocArray();
$strMailAssocs['chair']      = $objChair->getName(2);
$strMailAssocs['conference'] = $objConference->strName;
$strMailAssocs['review_dl']  = $objConference->strReviewDeadline;

$objPersons = $myDBAccess->getUsersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('gather list of users', $myDBAccess->getLastError());
}
foreach ($objPersons as $objPerson) {
  if ($objPerson->hasRole(REVIEWER) || $objPerson->hasRole(AUTHOR)) {
    if ($objPerson->hasRole(AUTHOR)) {
      $mail = new Template(TPLPATH.'mail_reviewreport_author.tpl');
      $objPapers = $myDBAccess->getPapersOfAuthor($objPerson->intId, session('confid'));
    }
    else {
      $mail = new Template(TPLPATH.'mail_reviewreport_reviewer.tpl');
      $objPapers = $myDBAccess->getPapersOfReviewer($objPerson->intId, session('confid'));
    }
    if ($myDBAccess->failed()) {
      error('gather list of papers of reviewer', $myDBAccess->getLastError());
    }
    $strMailAssocs['name'] = $objPerson->getName(2);    
    foreach ($objPapers as $objPaper) {
      $strMailAssocs['paper']           = $objPaper->strTitle;
      $strMailAssocs['status']          = ($objPaper->intStatus == PAPER_ACCEPTED ? 'accepted' : 'denied');
      $strMailAssocs['total_rating']    = round($objPaper->fltAvgRating * 100);
      $strMailAssocs['review_reports']  = '';
      $objReviewers = $myDBAccess->getReviewersOfPaper($objPaper->intId);
      if ($myDBAccess->failed()) {
        error('gather list of reviewers of paper', $myDBAccess->getLastError());
      }
      $intNumReviews = 0;
      foreach ($objReviewers as $objReviewer) {
        $intReviewId = $myDBAccess->getReviewIdOfReviewerAndPaper($objReviewer->intId, $objPaper->intId);
        if ($myDBAccess->failed()) {
          error('get review details', $myDBAccess->getLastError());
        }
        if (!empty($intReviewId)) {
          $objReview = $myDBAccess->getReviewDetailed($intReviewId);
          if ($myDBAccess->failed()) {
            error('get review details', $myDBAccess->getLastError());
          }
          else if (empty($objReview)) {
            error('get review details', 'Review does not exist in database.');
          }
          $review = new Template(TPLPATH.'mail_reviewreport.tpl');
          $strReviewAssocs = defaultAssocArray();
          $strReviewAssocs['paper']    = $objPaper->strTitle;
          $strReviewAssocs['reviewer'] = $objReviewer->getName(2);
          $strReviewAssocs['rating']   = round($objReview->fltReviewRating * 100);
          $strReviewAssocs['summary']  = $objReview->strSummary;
          $strReviewAssocs['remarks']  = $objReview->strRemarks;
          if ($objPerson->hasRole(AUTHOR)) {
            $strReviewAssocs['confidential'] = '';
            $strReviewAssocs['if'] = array();
          }
          else {
            $strReviewAssocs['confidential'] = $objReview->strConfidential;
            $strReviewAssocs['if'] = array(1);
          }
          $strReviewAssocs['crit_lines'] = '';
          for ($j = 0; $j < count($objReview->objCriterions); $j++) {
            $rating = new Template(TPLPATH.'mail_ratingline.tpl');
            $strRatingAssocs = defaultAssocArray();
            $strRatingAssocs['no']         = $j;
            $strRatingAssocs['crit_name']  = $objReview->objCriterions[$j]->strName;
            $strRatingAssocs['rating']     = $objReview->intRatings[$j];
            $strRatingAssocs['max_rating'] = $objReview->objCriterions[$j]->intMaxValue;
            $strRatingAssocs['comment']    = $objReview->strComments[$j];
            $rating->assign($strRatingAssocs);
            $rating->parse();
            $strMailAssocs['crit_lines'] .= $rating->getOutput();
          }          
          $review->assign($strReviewAssocs);
          $review->parse();
          $strMailAssocs['review_reports'] .= $review->getOutput();
          $intNumReviews++;
        }
      }      
      $strMailAssocs['num_reviews'] = $intNumReviews;
    }
    $mail->assign($strMailAssocs);
    $mail->parse();
    if (!sendMail($objPerson->intId, 'Review report for your papers', $mail->getOutput())) {
      $strMessage .= (!empty($strMessage) ? '<br>' : '');
      $strMessage .= 'Failed to send email to user '.$objPerson->getName(2).'!';
      $mainIfArray = array(2);
    }
  }
}

$content = new Template(TPLPATH.'confirm_notify_reviewers.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = $strMessage;
$strContentAssocs['if'] = $mainIfArray;
$content->assign($strContentAssocs);

include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Conferences Overview';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  '.
                             (session('menuitem') == 3 ? 'Papers' : 'Reviews');

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>