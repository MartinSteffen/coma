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
checkAccess(CHAIR);

if (isset($_GET['createforum'])) {  
  $startForum = createPaperForum($myDBAccess, $_GET['paperid']);
  if ($startForum) {
    redirect("forum.php?paperid=".$_GET['paperid']);
  }  
}

if (isset($_POST['action'])) {
  if ($_POST['action'] == 'changestatus') {
    $myDBAccess->setPaperStatus($_POST['paperid'],
      ($_POST['submit'] == 'accept' ? PAPER_ACCEPTED : PAPER_REJECTED));
    if ($myDBAccess->failed()) {
      error('updating paper status', $myDBAccess->getLastError());
    }
  }
  else if ($_POST['action'] == 'resetstatus') {
    $myDBAccess->resetPaperStatus($_POST['paperid']);
    if ($myDBAccess->failed()) {
      error('resetting paper status', $myDBAccess->getLastError());
    }
  }
/*else if ($_POST['action'] == 'delete') {
    $myDBAccess->deletePaper($_POST['paperid']);
    if ($myDBAccess->failed()) {
      error('deleting paper', $myDBAccess->getLastError());
    }
  }*/
}

if (isset($_GET['order'])) {
  if ((int)session('orderpapers', false) != $_GET['order']) {
    $_SESSION['orderpapers'] = $_GET['order'];
  }
  else {
    unset($_SESSION['orderpapers']);
  }
}
$intOrder = (int)session('orderpapers', false);
$ifArray = array($intOrder);

$objPapers = $myDBAccess->getPapersOfConference(session('confid'), $intOrder);
if ($myDBAccess->failed()) {
  error('gather list of reviews for chair', $myDBAccess->getLastError());
}

$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get conference details',$myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('get conference details', 'Conference '.session('confid').' does not exist in database.');
}

$content = new Template(TPLPATH.'chair_reviewlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['if'] = $ifArray;
$strContentAssocs['targetpage'] = 'chair_reviews.php';
$strContentAssocs['lines'] = '';
if (!empty($objPapers)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
    $strItemAssocs = defaultAssocArray();
    $ifArray = array();
    $strItemAssocs['line_no']     = $lineNo;
    $strItemAssocs['paper_id']    = encodeText($objPaper->intId);
    $strItemAssocs['author_id']   = encodeText($objPaper->intAuthorId);
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
    if (count($objReviewers) > 0) {    
      $strItemAssocs['num_reviews'] = encodeText($intRevs.' of '.count($objReviewers));
    }
    else {
      $strItemAssocs['num_reviews'] = 'none';
      $strItemAssocs['line_no']     = 'alert-'.$lineNo;
      $ifArray[] = 'ALERT';
    }
    if ($intRevs > 0) {
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
      $strItemAssocs['variance']   = encodeText(round($fltVariance * 100).'%');
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
    // Pruefe Zugang zum Paperforum
    if ($objPaper->intAuthorId != session('uid')) {    
      $objPaperForum = $myDBAccess->getForumOfPaper($objPaper->intId);
      if ($myDBAccess->failed()) {
        error('Error occured retrieving forum of paper.', $myDBAccess->getLastError());
      }      
      if (empty($objPaperForum)) {
        $ifArray[] = 8;
        $strItemAssocs['forum_id'] = '';
      }
      else {
        $ifArray[] = 9;
        $strItemAssocs['forum_id'] = encodeText($objPaperForum->intId);
      }
    }
    else {
      $strItemAssocs['forum_id'] = '';
    }
    $strItemAssocs['if'] = $ifArray;    
    // Zugeteilte Reviewer
    $strItemAssocs['reviewers'] = '';
    $assignedReviewers = new Template(TPLPATH.'chair_reviewlistreviewers.tpl');
    $strReviewersAssocs = defaultAssocArray();
    for ($i = 0; $i < count($objReviewers); $i++) {
      $strReviewersAssocs['rev_id']   = encodeText($objReviewers[$i]->intId);
      $strReviewersAssocs['rev_name'] = encodeText($objReviewers[$i]->getName(1));
      $assignedReviewers->assign($strReviewersAssocs);
      $assignedReviewers->parse();
      $strItemAssocs['reviewers']    .= $assignedReviewers->getOutput();
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
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Reviews';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>