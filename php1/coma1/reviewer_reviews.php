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
require_once('./include/paperdiscussion.inc.php');

// Pruefe Zugriffsberechtigung auf die Seite
checkAccess(REVIEWER);

$objPapers = $myDBAccess->getPapersOfReviewer(session('uid'), session('confid'));
if ($myDBAccess->failed()) {
  error('gather list of reviews for reviewer', $myDBAccess->getLastError());
}

// Hole Konferenz fuer Contraintsbestimmungen
$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get conference details', $myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('get conference details', 'Conference '.session('confid').' does not exist in database.');
}

if (isset($_GET['createforum'])) {  
  $startForum = createPaperForum($myDBAccess, $_GET['paperid']);
  if ($startForum) {
    redirect("forum.php?paperid=".$_GET['paperid']);
  }  
}

$content = new Template(TPLPATH.'reviewer_reviewlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['lines'] = '';
// Liste der Reviews erzeugen
if (!empty($objPapers)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
    $ifArray = array();
    $ifArray[] = $objPaper->intStatus;
    $strItemAssocs = defaultAssocArray();
    $strItemAssocs['line_no']     = $lineNo;
    $strItemAssocs['paper_id']    = encodeText($objPaper->intId);
    $strItemAssocs['title']       = encodeText($objPaper->strTitle);
    $strItemAssocs['author_id']   = encodeText($objPaper->intAuthorId);
    $strItemAssocs['author_name'] = encodeText($objPaper->strAuthor);        
    if (strtotime("now") < strtotime($objConference->strReviewDeadline)) {
      $ifArray[] = 6;
    }
    $isReviewed = $myDBAccess->hasPaperBeenReviewed($objPaper->intId, session('uid'));
    if ($myDBAccess->failed()) {
      error('check review status',$myDBAccess->getLastError());
    }    
    if ($isReviewed) {
      $paperItem = new Template(TPLPATH.'reviewer_reviewlistitem.tpl');
      $intReviewId = $myDBAccess->getReviewIdOfReviewerAndPaper(session('uid'), $objPaper->intId);
      if ($myDBAccess->failed()) {
        error('gather list of reviews for reviewer', $myDBAccess->getLastError());
      }
      else if (empty($intReviewId)) {
         error('gather list of reviews for reviewer', 'Review '.$intReviewId.' does not exist in database.');
      }
      $objReview = $myDBAccess->getReview($intReviewId);
      if ($myDBAccess->failed()) {
        error('gather list of reviews for reviewer', $myDBAccess->getLastError());
      }
      else if (empty($objReview)) {
         error('gather list of reviews for reviewer', 'Review '.$intReviewId.' does not exist in database.');
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
      // Pruefe Zugang zum Paperforum
      $objPaperForum = $myDBAccess->getForumOfPaper($objReview->intPaperId);
      if ($myDBAccess->failed()) {
        error('get forum of paper', $myDBAccess->getLastError());
      }      
      if (empty($objPaperForum)) {
      	$ifArray[] = 7;
      }
      else {
      	$ifArray[] = 8;
      	$strItemAssocs['forum_id'] = $objPaperForum->intId;
      }
    }
    else {
      $paperItem = new Template(TPLPATH.'reviewer_newreviewlistitem.tpl');
    }
    $strItemAssocs['if'] = $ifArray;
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