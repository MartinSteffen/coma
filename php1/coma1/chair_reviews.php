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
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), CHAIR);
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');	
}

if (isset($_POST['action'])) {
  if ($_POST['action'] == 'changestatus') {
    $myDBAccess->updatePaperStatus($_POST['paperid'],
      ($_POST['submit'] == 'accept' ? PAPER_ACCEPTED : PAPER_REJECTED));
    if ($myDBAccess->failed()) {
      error('Error updating paper status.', $myDBAccess->getLastError());
    }
  }
  else if ($_POST['action'] == 'resetstatus') {
    $myDBAccess->resetPaperStatus($_POST['paperid']);
    if ($myDBAccess->failed()) {
      error('Error resetting paper status.', $myDBAccess->getLastError());
    }
  }
/*else if ($_POST['action'] == 'delete') {  
    $myDBAccess->deletePaper($_POST['paperid']);
    if ($myDBAccess->failed()) {
      error('Error deleting paper.', $myDBAccess->getLastError());
    }
  }*/  
}

$objPapers = $myDBAccess->getPapersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get review list of chair',$myDBAccess->getLastError());
}

$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get conference details',$myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('get conference details', 'Conference does not exist in database.');
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
    $objReviewers = $myDBAccess->getReviewersOfPaper($objPaper->intId);
    if ($myDBAccess->failed()) {
      error('get review list of chair',$myDBAccess->getLastError());
    }
    $strItemAssocs['num_reviews'] = encodeText($intRevs.' of '.count($objReviewers));
    if (!empty($objPaper->fltAvgRating)) {
      $strItemAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 100).'%');
    }
    else {
      $strItemAssocs['avg_rating'] = ' - ';
    }
    $fltVariance = $myDBAccess->getVarianceOfPaper($objPaper->intId);
    if ($myDBAccess->failed()) {
      error('get paper list of chair', $myDBAccess->getLastError());
    }
    if (!empty($fltVariance) || is_numeric($fltVariance)) {
      $strItemAssocs['variance'] = encodeText(round($fltVariance * 100).'%');
      if ($fltVariance >= $objConference->fltCriticalVariance) {
        $ifArray[] = 6;
      }
      else {
        $ifArray[] = 7;
      }
    }
    else {
      $strItemAssocs['variance'] = ' - ';
      $ifArray[] = 7;
    }
    $strItemAssocs['if'] = $ifArray;
    // Zugeteilte Reviewer
    $strItemAssocs['reviewers'] = '';
    $assignedReviewers = new Template(TPLPATH.'chair_reviewlistreviewers.tpl');
    $strReviewersAssocs = defaultAssocArray();
    for ($i = 0; $i < count($objReviewers); $i++) {
      $strReviewersAssocs['rev_id'] = encodeText($objReviewers[$i]->intId);
      $strReviewersAssocs['rev_name'] = encodeText($objReviewers[$i]->getName(1));
      $assignedReviewers->assign($strReviewersAssocs);
      $assignedReviewers->parse();
      $strItemAssocs['reviewers'] .= $assignedReviewers->getOutput();
    }
    
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

$_SESSION['menu'] = CHAIR;
$_SESSION['menuitem'] = 4;
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