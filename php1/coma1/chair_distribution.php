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

// Pruefe Zugriffsberechtigung auf die Seite
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), CHAIR);
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');	
}

if (isset($_POST['action'])) {
  echo('IS SET action');
  if($_POST['action'] != 'confirm' || !isset($_SESSION['dist'])) {
    redirect('chair_reviews.php');
  }
  $dist = $_SESSION['dist'];
  foreach ($dist as $pid => $arrR) {
    print_r($pid.'=>'.$arrR);
/*    for ($j = 0; $j < count($arrR); $j++) {
      if ($arrR[$j]['status'] != ASSIGNED) {
        if(!isset($_POST['p'.$pid.'ridx'.$j])) {
          unset($arrR[$j]);
        }
      }
      print_r($arrR);
    }*/
    echo('<br>.');
  }
  die(-1);
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
      $strReviewersAssocs['status'] = rand(1,5)<=1?0:$arrReviewers[$i]['status'];
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