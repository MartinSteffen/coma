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

// Lade die Daten der Reviews des Papers
if (isset($_GET['paperid'])) {
  $objPaper = $myDBAccess->getPaperDetailed($_GET['paperid']);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving paper.', $myDBAccess->getLastError());
  }
  else if (empty($objPaper)) {
    error('Paper does not exist in database.', '');
  }
  $objReviews = $myDBAccess->getReviewsOfPaper($objPaper->intId);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving review report.', $myDBAccess->getLastError());
  }
  $objCriterions = $myDBAccess->getCriterionsOfConference(session('confid'));
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving rating criteria.', $myDBAccess->getLastError());
  }
}
else {
  redirect('chair_reviews.php');
}

$ifArray = array();
$content = new Template(TPLPATH.'chair_paperreviewlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['paper_id'] = encodeText($objPaper->intId);
$strContentAssocs['author_id'] = encodeText($objPaper->intAuthorId);
$strContentAssocs['author_name'] = encodeText($objPaper->strAuthor);
$strContentAssocs['cols'] = encodeText(count($objCriterions) + 3);
$strContentAssocs['cols-2'] = encodeText(count($objCriterions));
$strContentAssocs['reviews_num'] = encodeText(count($objReviews));
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
if (!empty($objReviews)) {  
  foreach ($objReviews as $objReview) {  	
    $objReviewDetailed = $myDBAccess->getReviewDetailed($objReview->intId);
    if ($myDBAccess->failed()) {
      error('Error occured during retrieving review details.', $myDBAccess->getLastError());
    }
    else if (empty($objReviewDetailed)) {
      error('Review does not exist in database.', '');
    }
    $strRowAssocs = defaultAssocArray();
    $strRowAssocs['review_id'] = encodeText($objReview->intId);
    $strRowAssocs['reviewer_name'] = encodeText($objReview->strReviewerName);
    $strRowAssocs['reviewer_id'] = encodeText($objReview->intReviewerId);
    $strRowAssocs['total_rating'] = encodeText(round($objReview->fltReviewRating * 100).'%');
    $strRowAssocs['rating_cols'] = '';    
    for ($i = 0; $i < count($objReviewDetailed->intRatings); $i++) {
      $strColAssocs = defaultAssocArray();
      $strColAssocs['content'] = encodeText($objReviewDetailed->intRatings[$i]).'/'.
                                 encodeText($objReviewDetailed->objCriterions[$i]->intMaxValue);
      $colItem = new Template(TPLPATH.'view_tablecell.tpl');
      $colItem->assign($strColAssocs);
      $colItem->parse();
      $strRowAssocs['rating_cols'] .= $colItem->getOutput();    
    }
    $rowItem = new Template(TPLPATH.'chair_paperreviewlistitem.tpl');
    $rowItem->assign($strRowAssocs);
    $rowItem->parse();
    $strContentAssocs['review_lines'] .= $rowItem->getOutput();    
  }
}

// Pruefe noch, ob der reviewte Artikel kritisch ist.

$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$actMenu = CHAIR;
$actMenuItem = 3;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Review report for paper';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Paper  |  Review report';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>