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

/*if (isset($_POST['action']) && $_POST['action'] == 'delete') {  
  $myDBAccess->deletePaper($_POST['paperid']);
  if ($myDBAccess->failed()) {
    error('Error deleting paper.', $myDBAccess->getLastError());
  }
}*/

$myDist = new Distribution($mySql);
$dist = $myDist->getDistribution(session('confid'));
if ($myDist->failed()) {
  error('get distribution suggestion',$myDist->getLastError());
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
      $strReviewersAssocs['rev_id'] = $arrReviewers[$i]['reviewer_id'];
      $strReviewersAssocs['rev_name'] = $objReviewer->getName(1);
      $strReviewersAssocs['status'] = rand(1,5)<=1?0:$arrReviewers[$i]['status'];
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
$strMainAssocs['title'] = 'Distribution suggestion';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>