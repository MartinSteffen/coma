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

$objPapers = $myDBAccess->getPapersOfReviewer(session('uid'), session('confid'));
if ($myDBAccess->failed()) {
  error('get review list of reviewer',$myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'reviewer_reviewlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['lines'] = '';
// Liste der Reviews erzeugen
if (!empty($objPapers)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
  	$isReviewed = $myDBAccess->hasPaperBeenReviewed($objPaper->intId, session('uid'));
  	if ($myDBAccess->failed()) {
      error('Error during review status check.',$myDBAccess->getLastError());
    }
    $ifArray = array();
    $ifArray[] = $objPaper->intStatus;
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['paper_id'] = encodeText($objPaper->intId);
    $strItemAssocs['title'] = encodeText($objPaper->strTitle);
    $strItemAssocs['author_id'] = encodeText($objPaper->intAuthorId);
    $strItemAssocs['author_name'] = encodeText($objPaper->strAuthor);
    $strItemAssocs['if'] = $ifArray;
    if ($isReviewed) {
    	$paperItem = new Template(TPLPATH.'reviewer_reviewlistitem.tpl');
  	  $objReview = $myDBAccess->getReviewerReviewOfPaper(session('uid'),$objPaper->intId);
      if ($myDBAccess->failed()) {
        error('Error receiving review list of reviewer.',$myDBAccess->getLastError());
      }
      else if (empty($objReview)) {
      	 error('Error receiving review list of reviewer.', 'Review does not exist in database.');
      }
      $strItemAssocs['review_id'] = encodeText($objReview->intId);
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
    }
    else {
      $paperItem = new Template(TPLPATH.'reviewer_reviewlistitem.tpl');
    }
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