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

/*if (isset($_POST['action']) && $_POST['action'] == 'delete') {  
  $myDBAccess->deletePaper($_POST['paperid']);
  if ($myDBAccess->failed()) {
    error('Error deleting paper.', $myDBAccess->getLastError());
  }
}*/

$objPapers = $myDBAccess->getPapersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get review list of chair',$myDBAccess->getLastError());
}

$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get review list of chair',$myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'chair_reviewlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['if'] = array();
$strContentAssocs['lines'] = '';
if (!empty($objPapers)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
    $strItemAssocs = defaultAssocArray();
    $ifArray = array();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['paper_id'] = encodeText($objPaper->intId);
    $strItemAssocs['author_id'] = encodeText($objPaper->intAuthorId);
    $strItemAssocs['author_name'] = encodeText($objPaper->strAuthor);      
    $ifArray[] = $objPaper->intStatus;
    if (!empty($objPaper->strFilePath)) {
      $ifArray[] = 5;
    }
    $strItemAssocs['title'] = encodeText($objPaper->strTitle);
    $intRevs = $myDBAccess->getNumberOfReviewsOfPaper($objPaper->intId);
    if ($myDBAccess->failed()) {
      error('get review list of chair',$myDBAccess->getLastError());
    }
    $objReviewers = $myDBAccess->getAssignedReviewersOfPaper($objPaper->intId);
    if ($myDBAccess->failed()) {
      error('get review list of chair',$myDBAccess->getLastError());
    }
    $strItemAssocs['num_reviews'] = encodeText($intRevs.'/'.count($objReviewers));
    if (!empty($objPaper->fltAvgRating)) {
      $strItemAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 100).'%');
    }
    else {
      $strItemAssocs['avg_rating'] = ' - ';
    }
    $fltTestTmp = rand(0,100);
    if ($fltTestTmp >= $objConference->fltCriticalVariance*100) {
      $ifArray[] = 6;
    }
    else {
      $ifArray[] = 7;
    }
    $strItemAssocs['crit_value'] = $fltTestTmp/100;
    $strItemAssocs['if'] = $ifArray;
    // Zugeteilte Reviewer
    $strReviewersAssocs = defaultAssocArray();
    $assIfArray = array();
    for ($i = 0; $i < count($objReviewers); $i++) {
      if ($i > 0) {
        $assIfArray[] = 1;
      }
      $strReviewersAssocs['rev_id'] = $objReviewers[$i]->intId;
      $strReviewersAssocs['rev_name'] = $objReviewers[$i]->getName();
    }
    $strReviewersAssocs['if'] = $assIfArray;
    $assignedReviewers = new Template(TPLPATH.'chair_reviewlistreviewers.tpl');
    $assignedReviewers->assign($strReviewersAssocs);
    $assignedReviewers->parse();
    $strItemAssocs['reviewers'] = $assignedReviewers->getOutput();
    
    $paperItem = new Template(TPLPATH.'chair_reviewlistitem.tpl');
    $paperItem->assign($strItemAssocs);
    $paperItem->parse();
    $strContentAssocs['lines'] .= $paperItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {
  // Artikelliste ist leer.
  $strItemAssocs = defaultAssocArray();
  $strItemAssocs['colspan'] = '8';
  $strItemAssocs['text'] = 'There are no papers available.';
  $emptyList = new Template(TPLPATH.'empty_list.tpl');
  $emptyList->assign($strItemAssocs);
  $emptyList->parse();
  $strContentAssocs['lines'] = $emptyList->getOutput();  
}

$content->assign($strContentAssocs);

$actMenu = CHAIR;
$actMenuItem = 4;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Distribute and manage papers';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>