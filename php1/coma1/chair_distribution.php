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

if (isset($_POST['confirm']) || isset($_POST['dismiss'])) {
  if (!isset($_SESSION['dist']) || isset($_POST['dismiss'])) {
    unset($_SESSION['dist']);
    redirect('chair_reviews.php');
  }
  $dist = $_SESSION['dist'];
  reset($dist);
  while ($pid = key($dist)) {
    for ($j = 0; $j < count($dist[$pid]); $j++) {
      if(isset($_POST['p'.$pid.'ridx'.$j]) && $dist[$pid][$j]['status'] != ASSIGNED) {
        $myDBAccess->addDistribution($dist[$pid][$j]['reviewer_id'], $pid);
        if ($myDBAccess->failed()) {
          error('Error occured while adding distribution data', $myDBAccess->getLastError());
        }
      }
      elseif (!isset($_POST['p'.$pid.'ridx'.$j]) && $dist[$pid][$j]['status'] == ASSIGNED) {
        $myDBAccess->deleteDistribution($dist[$pid][$j]['reviewer_id'], $pid);
        if ($myDBAccess->failed()) {
          error('Error occured while deleting distribution data', $myDBAccess->getLastError());
        }
      }
    }
    next($dist);
  }
  unset($_SESSION['dist']);
  redirect('chair_reviews.php');
}
else {
  // Kritischen Papers automatisch mehr Reviewer hinzufuegen
  $objConference = $myDBAccess->getConferenceDetailed(session('confid'));
  if ($myDBAccess->failed()) {
    error('get conference data', $myDBAccess->getLastError());
  }
  if ($objConference->blnAutoAddReviewers) {
    $intPapers = $myDBAccess->getCriticalPaperIds(session('confid'));
    if ($myDBAccess->failed()) {
      error('get critical papers of conference', $myDBAccess->getLastError());
    }
    $intWantedReviewers = false;
    for ($i = 0; $i < count($intPapers); $i++) {
      $intWantedReviewers[$intPapers[$i]] = $objConference->intDefaultReviewsPerPaper +
                                            $objConference->intNumberOfAutoAddReviewers;
    }
    print_r($intWantedReviewers);
    die();
  }
  $dist = $myDist->getDistribution(session('confid'));
  if ($myDist->failed()) {
    error('get distribution suggestion',$myDist->getLastError());
  }
}

$content = new Template(TPLPATH.'chair_distributionlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['if'] = array();
$strContentAssocs['lines'] = '';
if (!empty($dist)) {
  $lineNo = 1;
  $objPapers = $myDBAccess->getPapersOfConference(session('confid'));
  if ($myDBAccess->failed()) {
    error('get distribution paper list of chair',$myDBAccess->getLastError());
  }
  foreach ($objPapers as $objPaper) {
    $strItemAssocs = defaultAssocArray();
    $ifArray = array();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['paper_id'] = encodeText($objPaper->intId);
    $strItemAssocs['author_id'] = encodeText($objPaper->intAuthorId);
    $strItemAssocs['author_name'] = encodeText($objPaper->strAuthor);
    $strItemAssocs['title'] = encodeText($objPaper->strTitle);
    $strItemAssocs['if'] = $ifArray;
    // Zugeteilte Reviewer
    $strItemAssocs['reviewers'] = '';
    $assignedReviewers = new Template(TPLPATH.'chair_distributionlistreviewers.tpl');
    $strReviewersAssocs = defaultAssocArray();
    $arrReviewers = $dist[$objPaper->intId];
    for ($i = 0; $i < count($arrReviewers); $i++) {
      $objReviewer = $myDBAccess->getPerson($arrReviewers[$i]['reviewer_id']);
      if ($myDBAccess->failed()) {
        error('get suggested reviewer',$myDBAccess->getLastError());
      }
      $strReviewersAssocs['paper_id'] = encodeText($objPaper->intId);
      $strReviewersAssocs['rev_id'] = encodeText($arrReviewers[$i]['reviewer_id']);
      $strReviewersAssocs['rev_name'] = encodeText($objReviewer->getName(1));
      $strReviewersAssocs['status'] = encodeText($arrReviewers[$i]['status']);
      $strReviewersAssocs['rev_array_index'] = $i;
      $strReviewersAssocs['if'] =
        array(1); //($arrReviewers[$i]['status']==ASSIGNED?array():array(1));
      $assignedReviewers->assign($strReviewersAssocs);
      $assignedReviewers->parse();
      $strItemAssocs['reviewers'] .= $assignedReviewers->getOutput();
    }
    $paperItem = new Template(TPLPATH.'chair_distributionlistitem.tpl');
    $paperItem->assign($strItemAssocs);
    $paperItem->parse();
    $strContentAssocs['lines'] .= $paperItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
  $_SESSION['dist'] = $dist;
}
else {
  // Artikelliste ist leer.
  $strItemAssocs = defaultAssocArray();
  $strItemAssocs['colspan'] = '8';
  $strItemAssocs['text'] = 'Could not find a valid distribution or no papers available.';
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
$strMainAssocs['title'] = 'Distribution suggestion';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Reviews  |  Distribution';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>