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
require_once(INCPATH.'class.distribution.inc.php');

// Pruefe Zugriffsberechtigung auf die Seite
checkAccess(CHAIR);

if (!isset($_GET['paperid'])) {
  error('Get paper ID', 'Not found.');
}
$pid = $_GET['paperid'];
// Pruefe ob das Paper zur akt. Konferenz gehoert
checkPaper($pid);

$r_id = $myDist->getAvailableReviewerIdsOfConference(session('confid'));
if ($myDist->failed()) {
  error('get list of available reviewers', $myDist->getLastError());
}

if (isset($_POST['action']) && $_POST['action'] == 'submit') {
  $s = '';
  foreach ($r_id as $rid) {
    $isD = $myDBAccess->isPaperDistributedTo($pid, $rid);
    if ($myDBAccess->failed()) {
      error('get paper/reviewer information', $myDBAccess->getLastError());
    }
    if (isset($_POST['p'.$pid.'r'.$rid]) && !$isD) {
      $myDBAccess->addDistribution($rid, $pid);
      if ($myDBAccess->failed()) {
        error('adding distribution', $myDBAccess->getLastError());
      }
    }
    elseif (!isset($_POST['p'.$pid.'r'.$rid]) && $isD) {
      $myDBAccess->deleteDistribution($rid, $pid);
      if ($myDBAccess->failed()) {
        error('deleting distribution', $myDBAccess->getLastError());
      }
    }
  }
}

$objPaper = $myDBAccess->getPaperSimple($pid);
if ($myDBAccess->failed()) {
  error('get paper', $myDBAccess->getLastError());
}
elseif (empty($objPaper)) {
  error('get paper', 'Paper '.$pid.' does not exist in database.');
}
$objTopics = $myDBAccess->getTopicsOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('gather list of topics', $myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'chair_reviewerassignment.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['paper_id'] = encodeText($objPaper->intId);
$strContentAssocs['if'] = array();
$strContentAssocs['reviewer_lines'] = '';
if (!empty($r_id)) {
	$lineNo = 1;
  foreach ($r_id as $rid) {    
    $objReviewer = $myDBAccess->getPerson($rid);
    if ($myDBAccess->failed()) {
      error('get reviewer data', $myDBAccess->getLastError());
    }
    $objReviewerAttitude = $myDBAccess->getReviewerAttitude($rid, session('confid'));
    if ($myDBAccess->failed()) {
      error('get reviewer attitude mapping', $myDBAccess->getLastError());
    }
    $strItemAssocs = defaultAssocArray();
    $strItemAssocs['line_no']  = $lineNo;
    $strItemAssocs['paper_id'] = encodeText($objPaper->intId);
    $strItemAssocs['rev_id']   = encodeText($objReviewer->intId);
    $strItemAssocs['rev_name'] = encodeText($objReviewer->getName(1));
    $intNum = $myDBAccess->getNumberOfPapersOfReviewer($rid, session('confid'));
    if ($myDBAccess->failed()) {
      error('get number of papers of reviewer', $myDBAccess->getLastError());
    }
    $strItemAssocs['num_papers'] = encodeText($intNum);

    $strItemAssocs['topics'] = '';
    for ($i = 0; $i < count($objTopics); $i++) {
      if ($objReviewerAttitude->getTopicAttitude($objTopics[$i]->intId) == ATTITUDE_PREFER) {
        $strItem2Assocs = defaultAssocArray();
        $strItem2Assocs['topic'] = $objTopics[$i]->strName;
        $strItem2Assocs['if'] = array(0);
        for ($j = 0; $j < count($objPaper->objTopics); $j++) {
          if ($objTopics[$i]->intId == $objPaper->objTopics[$j]->intId) {
            $strItem2Assocs['if'] = array(1);
            break;
          }
        }
        $strTopicItem = new Template(TPLPATH.'reviewertopic.tpl');
        $strTopicItem->assign($strItem2Assocs);
        $strTopicItem->parse();
        $strItemAssocs['topics'] .= $strTopicItem->getOutput();
      }
    }    

    $strItemAssocs['if'] = array($objReviewerAttitude->getPaperAttitude($objPaper->intId));
    $isD = $myDBAccess->isPaperDistributedTo($objPaper->intId, $objReviewer->intId);
    if ($myDBAccess->failed()) {
      error('get paper/reviewer information', $myDBAccess->getLastError());
    }
    if ($isD) {
      $strItemAssocs['if'][] = 8; // Checkbox mit Haekchen setzen
    }
    elseif ($objReviewerAttitude->getPaperAttitude($objPaper->intId) != ATTITUDE_EXCLUDE) {
      $strItemAssocs['if'][] = 7; // Checkbox ohne Haekchen setzen
    }
    $strRevItem = new Template(TPLPATH.'reviewerattitudes.tpl');
    $strRevItem->assign($strItemAssocs);
    $strRevItem->parse();
    $strContentAssocs['reviewer_lines'] .= $strRevItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {
  // Artikelliste ist leer.
  $strItemAssocs = defaultAssocArray();
  $strItemAssocs['colspan'] = '4';
  $strItemAssocs['text'] = 'There are no papers available.';
  $emptyList = new Template(TPLPATH.'empty_list.tpl');
  $emptyList->assign($strItemAssocs);
  $emptyList->parse();
  $strContentAssocs['lines'] = $emptyList->getOutput();
}

$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $strContentAssocs['if'] = array(9);
}
$strContentAssocs['targetpage'] = 'chair_reviewsreviewer.php';
$content->assign($strContentAssocs);

$_SESSION['menu'] = CHAIR;
$_SESSION['menuitem'] = 4;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Assigned reviewers of paper \''.encodeText($objPaper->strTitle).'\'';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Reviews  |  Distribution';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>