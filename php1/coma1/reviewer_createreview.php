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
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), REVIEWER);
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');
}

$objCriterions = $myDBAccess->getCriterionsOfConference(session('confid'));
if ($myDBAccess->failed()) {
  return $this->error('Error receiving rating criterions of conference', $myDBAccess->getLastError());
}

if (!isset($_GET['paperid']) && !isset($_POST['paperid'])) {
  redirect("reviewer_reviews.php");
}

$intPaperId = (isset($_GET['paperid']) ? $_GET['paperid'] : $_POST['paperid']);
$objPaper = $myDBAccess->getPaperDetailed($intPaperId);
if ($myDBAccess->failed()) {
  error('Error occured retrieving paper.', $myDBAccess->getLastError());
}
else if (empty($objPaper)) {
  error('Create review report for paper','Paper '.$intPaperId.' does not exist in database!');
}
$objReviewer = $myDBAccess->getPerson(session('uid'));
if ($myDBAccess->failed()) {
  error('Error occured retrieving reviewer.', $myDBAccess->getLastError());
}
else if (empty($objReviewer)) {
  error('Create review report for paper','User does not exist in database!');
}

$checkReview = $myDBAccess->isPaperDistributedTo($objPaper->intId, session('uid')) &&
              !$myDBAccess->hasPaperBeenReviewed($objPaper->intId, session('uid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkReview) {
  error('You have no permission to view this page.', '');
}

$intRatings  = array();
$strComments = array();

// Verarbeite mitgeschickte Daten
if (isset($_POST['action'])) {
  for ($i = 0; $i < count($objCriterions) && !empty($objCriterions); $i++) {
    $intRatings[]  = (int)$_POST['rating-'.($i+1)];
    $strComments[] = $_POST['comment-'.($i+1)];
  }
  $strSummary      = $_POST['summary'];
  $strConfidential = $_POST['confidential'];
  $strRemarks      = $_POST['remarks'];
  $fltRating       = $_POST['rating'];
  // Teste Gueltigkeit der Daten
  $noError = true;
  for ($i = 0; $i < count($intRatings) && $noError; $i++) {
    if ($intRatings[$i] < 0 ||
        $intRatings[$i] > $objCriterions[$i]->intMaxValue) {
      $noError = false;
      $strMessage = 'There are invalid ratings. Please check if all ratings are '.
                    'within their respective range.';
    }
  }
  if ($noError) {
    $fltRating = 0.0;
    // Berechne Gesamtbewertung neu
    for ($i = 0; $i < count($intRatings); $i++) {
      $fltRating += (($intRatings[$i] / $objCriterions[$i]->intMaxValue) *
                     $objCriterions[$i]->fltWeight);
    }
    // Uebernimm die Aenderungen
    if (isset($_POST['submit']) ) {
      // Trage Review in die Datenbank ein
      $result = $myDBAccess->createNewReviewReport($objPaper->intId, $objReviewer->intId,
                                                   $strSummary, $strRemarks, $strConfidential,
                                                   $intRatings, $strComments, $objCriterions);
      if (!empty($result)) {
        $_SESSION['message'] = 'Review report was created successfully.';
        redirect("reviewer_editreview.php?reviewid=".$result);
      }
      else if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error during creating review report.', $myDBAccess->getLastError());
      }
    }
  }
}
else {
  $fltRating = 0.0;
  for ($i = 0; $i < count($objCriterions) && !empty($objCriterions); $i++) {
    $intRatings[]  = 0;
    $strComments[] = '';
  }
  $strSummary      = '';
  $strConfidential = '';
  $strRemarks      = '';
}

$content = new Template(TPLPATH.'edit_review.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['targetpage']   = "reviewer_createreview.php";
$strContentAssocs['message'] = '';
$strContentAssocs['review_id']    = encodeText(0);
$strContentAssocs['paper_id']     = encodeText($objPaper->intId);
$strContentAssocs['title']        = encodeText($objPaper->strTitle);
$strContentAssocs['author_id']    = encodeText($objPaper->intAuthorId);
$strContentAssocs['author_name']  = encodeText($objPaper->strAuthor);
$strContentAssocs['rating']       = encodeText(round($fltRating * 100));
$strContentAssocs['summary']      = encodeText($strSummary);
$strContentAssocs['confidential'] = encodeText($strConfidential);
$strContentAssocs['remarks']      = encodeText($strRemarks);
$strContentAssocs['crit_lines']   = '';
for ($i = 0; $i < count($objCriterions); $i++) {
  $critForm = new Template(TPLPATH.'review_critlistitem.tpl');
  $strCritAssocs = defaultAssocArray();
  $strCritAssocs['crit_no']    = encodeText($i+1);
  $strCritAssocs['crit_id']    = encodeText($objCriterions[$i]->intId);
  $strCritAssocs['crit_name']  = encodeText($objCriterions[$i]->strName);
  $strCritAssocs['crit_descr'] = encodeText($objCriterions[$i]->strDescription);
  $strCritAssocs['crit_max']   = encodeText($objCriterions[$i]->intMaxValue);
  $strCritAssocs['rating']     = encodeText($intRatings[$i]);
  $strCritAssocs['comment']    = encodeText($strComments[$i]);
  if ($intRatings[$i] < 0 ||
      $intRatings[$i] > $objCriterions[$i]->intMaxValue) {
    $strCritAssocs['if'] = array(1);
  }
  $critForm->assign($strCritAssocs);
  $critForm->parse();
  $strContentAssocs['crit_lines'] .= $critForm->getOutput();
}
if (isset($strMessage) && !empty($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $strContentAssocs['if'] = array(2, 9);
}
else {
  $strContentAssocs['if'] = array(2);
}
$content->assign($strContentAssocs);

include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Create review';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Reviewer  |  Reviews';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>