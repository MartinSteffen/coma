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

$objReviews = $myDBAccess->getReviewsOfReviewer(session('uid'), session('confid'));
if ($myDBAccess->failed()) {
  error('get review list of reviewer',$myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'reviewer_reviewlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['lines'] = '';
if (!empty($objReviews)) {
  $lineNo = 1;
  foreach ($objReviews as $objReview) {
    $ifArray = array();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['review_id'] = encodeText($objReview->intId);
    $strItemAssocs['paper_id'] = encodeText($objReview->intPaperId);
    $strItemAssocs['title'] = encodeText($objReview->strPaperTitle);
    $strItemAssocs['author_id'] = encodeText($objReview->intAuthorId);
    $strItemAssocs['author_name'] = encodeText($objReview->strAuthorName);
    $objPaper = $myDBAccess->getPaperSimple($objReview->intPaperId);
    if ($myDBAccess->failed()) {
      error('get paper in review list of reviewer', $myDBAccess->getLastError());
    }
    else if (!empty($objPaper)) {
      $ifArray[] = $objPaper->intStatus;
    }
    if (!empty($objReview->fltReviewRating)) {
      $strItemAssocs['rating'] = encodeText(round($objReview->fltReviewRating * 100).'%');
    }
    else {
      $strItemAssocs['rating'] = ' - ';
    }
    if (!empty($objReview->fltAverageRating)) {
      $strItemAssocs['avg_rating'] = encodeText(round($objReview->fltAverageRating * 100).'%');
    }
    else {
      $strItemAssocs['avg_rating'] = ' - ';
    }
    $strItemAssocs['if'] = $ifArray;
    $paperItem = new Template(TPLPATH.'reviewer_reviewlistitem.tpl');
    $paperItem->assign($strItemAssocs);
    $paperItem->parse();
    $strContentAssocs['lines'] .= $paperItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {
  // Reviewliste ist leer.
  $strItemAssocs = defaultAssocArray();
  $strItemAssocs['colspan'] = '5';
  $strItemAssocs['text'] = 'There are no reviews available.';
  $emptyList = new Template(TPLPATH.'empty_list.tpl');
  $emptyList->assign($strItemAssocs);
  $emptyList->parse();
  $strContentAssocs['lines'] = $emptyList->getOutput();    
}

$content->assign($strContentAssocs);

$_SESSION['menu'] = REVIEWER;
$_SESSION['menuitem'] = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Manage reviews of reviewer '.encodeText(session('uname'));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Reviewer  |  Reviews';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>