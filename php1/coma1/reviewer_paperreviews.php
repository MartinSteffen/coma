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
checkAccess(REVIEWER);

// Lade die Daten der Reviews des Papers
if (isset($_GET['paperid']) || isset($_POST['paperid'])) {
  $intPaperId = isset($_GET['paperid']) ? $_GET['paperid'] : $_POST['paperid'];
  // Pruefe, ob das Paper zur aktuellen Konferenz gehoert
  checkPaper($intPaperId);
  $objPaper = $myDBAccess->getPaperDetailed($intPaperId);
  if ($myDBAccess->failed()) {
    error('get paper data', $myDBAccess->getLastError());
  }
  else if (empty($objPaper)) {
    error('get paper data', 'Paper '.$intPaperId.' does not exist in database.');
  }    
  $objReviews = $myDBAccess->getReviewsOfPaper($objPaper->intId);
  if ($myDBAccess->failed()) {
    error('get review report', $myDBAccess->getLastError());
  }
  // Pruefe ob der Reviewer den Reviewreport sehen darf
  $objReviewers = $myDBAccess->getReviewersOfPaper($objPaper->intId);
  if ($myDBAccess->failed()) {
    error('gather list of reviewers', $myDBAccess->getLastError());
  }
  $isReviewerOfPaper = false;
  for ($i = 0; $i < count($objReviewers) && !empty($objReviewers) && !$isReviewerOfPaper; $i++) {
    if ($objReviewers[$i]->intId == session('uid')) {
      $isReviewerOfPaper = true;
    }
  }
  if (!$isReviewerOfPaper) {
    error('You have no permission to view this page!', '');
  }  
  $objCriterions = $myDBAccess->getCriterionsOfConference(session('confid'));
  if ($myDBAccess->failed()) {
    error('gather rating criteria', $myDBAccess->getLastError());
  }
}
else {
  redirect('reviewer_reviews.php');
}

$ifArray = array();
$content = new Template(TPLPATH.'user_paperreviewlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['paper_id'] = encodeText($objPaper->intId);
$strContentAssocs['author_id'] = encodeText($objPaper->intAuthorId);
$strContentAssocs['author_name'] = encodeText($objPaper->strAuthor);
$strContentAssocs['colspan'] = encodeText(count($objCriterions));
$strContentAssocs['reviews_num'] = encodeText(count($objReviews));
$strContentAssocs['reviewers_num'] = encodeText(count($objReviewers));
$strContentAssocs['title'] = encodeText($objPaper->strTitle);
if (!empty($objPaper->fltAvgRating)) {
  $strContentAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 100).'%');
}
else {
  $strContentAssocs['avg_rating'] = ' - ';
}
if (isset($strMessage) && !empty($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $ifArray[] = 9;
}
else {
  $strContentAssocs['message'] = '';
}

$strContentAssocs['crit_cols'] = '';
if (!empty($objCriterions)) {
  foreach ($objCriterions as $objCriterion) {
    $strColAssocs = defaultAssocArray();
    $strColAssocs['content'] = encodeText($objCriterion->strName.':');
    $colItem = new Template(TPLPATH.'view_tableheader.tpl');
    $colItem->assign($strColAssocs);
    $colItem->parse();
    $strContentAssocs['crit_cols'] .= $colItem->getOutput();
  }
}
$strContentAssocs['review_lines'] = '';
if (!empty($objReviewers)) {
  foreach ($objReviewers as $objReviewer) {
    $strRowAssocs = defaultAssocArray();
    $strRowAssocs['reviewer_name'] = encodeText($objReviewer->getName(1));
    $strRowAssocs['reviewer_id'] = encodeText($objReviewer->intId);
    $intReviewId = $myDBAccess->getReviewIdOfReviewerAndPaper($objReviewer->intId, $objPaper->intId);
    if ($myDBAccess->failed()) {
      error('get review details', $myDBAccess->getLastError());
    }
    else if (!empty($intReviewId)) {
      $objReview = $myDBAccess->getReviewDetailed($intReviewId);
      if ($myDBAccess->failed()) {
        error('get review details', $myDBAccess->getLastError());
      }
      else if (empty($objReview)) {
        error('get review details', 'Review does not exist in database.');
      }
      $rowItem = new Template(TPLPATH.'user_paperreviewlistitem.tpl');
      $strRowAssocs['review_id'] = encodeText($objReview->intId);
      $strRowAssocs['reviewer_name'] = encodeText($objReview->strReviewerName);
      $strRowAssocs['reviewer_id'] = encodeText($objReview->intReviewerId);
      $strRowAssocs['total_rating'] = encodeText(round($objReview->fltReviewRating * 100).'%');
      $strRowAssocs['targetpage'] = 'reviewer_reviewdetails.php';
      $strRowAssocs['rating_cols'] = '';
      for ($i = 0; $i < count($objReview->intRatings); $i++) {
        $strColAssocs = defaultAssocArray();
        $strColAssocs['content'] = encodeText($objReview->intRatings[$i]).'/'.
                                   encodeText($objReview->objCriterions[$i]->intMaxValue);
        $colItem = new Template(TPLPATH.'view_tablecell.tpl');
        $colItem->assign($strColAssocs);
        $colItem->parse();
        $strRowAssocs['rating_cols'] .= $colItem->getOutput();
      }
    }
    else {
      $rowItem = new Template(TPLPATH.'chair_emptypaperreviewlistitem.tpl');
      $strRowAssocs['colspan'] = count($objCriterions);
    }
    $rowItem->assign($strRowAssocs);
    $rowItem->parse();
    $strContentAssocs['review_lines'] .= $rowItem->getOutput();
  }
}

// Pruefe noch, ob der reviewte Artikel kritisch ist.

$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Review report for paper';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Reviewer  |  Paper review report';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>