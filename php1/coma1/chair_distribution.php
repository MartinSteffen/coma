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
require_once('./include/class.distribution.inc.php');

redirect('chair_start.php');

// Pruefe Zugriffsberechtigung auf die Seite
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), CHAIR);
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');	
}

if (isset($_POST['confirm']) || isset($_POST['dismiss'])) {
//  if (!isset($_SESSION['dist'])) || !isset($_SESSION['dist_check']) ||
//      !isset($_POST['dist_check']) || isset($_POST['dismiss'])) {
    unset($_SESSION['dist']);
//    unset($_SESSION['dist_check']);
    redirect('chair_reviews.php');
//  }
/*  if ($_POST['dist_check'] != $_SESSION['dist_check']) {
    error('Conflict.', 'Distribution not updated.');
  }*/
  $dist = $_SESSION['dist'];
  reset($dist);
  while ($pid = key($dist)) {
    for ($j = 0; $j < count($dist[$pid]); $j++) {
      if ($dist[$pid][$j]['status'] != ASSIGNED) {
        if(isset($_POST['p'.$pid.'ridx'.$j])) {
          $myDBAccess->addDistribution($dist[$pid][$j]['reviewer_id'], $pid);
          if ($myDBAccess->failed()) {
            error('Error occured while adding distribution data.', $myDBAccess->getLastError());
          }
        }
      }
    }
    next($dist);
  }
  unset($_SESSION['dist']);
  unset($_SESSION['dist_check']);
  redirect('chair_reviews.php');
}
else {
  $myDist = new Distribution($mySql);
  $dist = $myDist->getDistribution(session('confid'));
  if ($myDist->failed()) {
    error('get distribution suggestion',$myDist->getLastError());
  }
}

$content = new Template(TPLPATH.'chair_distributionlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['if'] = array();
$strContentAssocs['lines'] = '';
$strContentAssocs['lines'] = '';
// damit man nicht aus zwei Fenstern sich widersprechende Distributions ausfuehren kann:
$_SESSION['dist_check'] = rand(1, 1000000);
$strContentAssocs['dist_check'] = $_SESSION['dist_check'];
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
      $strReviewersAssocs['paper_id'] = $objPaper->intId;
      $strReviewersAssocs['rev_id'] = $arrReviewers[$i]['reviewer_id'];
      $strReviewersAssocs['rev_name'] = $objReviewer->getName(1);
      $strReviewersAssocs['status'] = $arrReviewers[$i]['status'];
      $strReviewersAssocs['rev_array_index'] = $i;
      $strReviewersAssocs['if'] =
        ($arrReviewers[$i]['status']==ASSIGNED?array():array(1));
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
$strMainAssocs['title'] = 'Distribution suggestion';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>