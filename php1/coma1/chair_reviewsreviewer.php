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
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), REVIEWER);
if ($myDBAccess->failed()) {
  error('Error while checking access permission', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page', '');
}

if (!isset($_GET['paperid'])) {
  error('Get paper ID', 'Not found.');
}
$objPaper = $myDBAccess->getPaperSimple($_GET['paperid']);
if ($myDBAccess->failed()) {
  error('get paper', $myDBAccess->getLastError());
}
elseif (empty($objPaper)) {
  error('get paper', 'Empty result.');
}
$objTopics = $myDBAccess->getTopicsOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get topic list',$myDBAccess->getLastError());
}
$r_id = $myDist->getAvailableReviewerIdsOfConference(session('confid'));
if ($myDist->failed()) {
  error('get topic list',$myDist->getLastError());
}

/*if (isset($_POST['action']) && $_POST['action'] == 'submit') {
  foreach ($objPapers as $objPaper) {
    $objReviewerAttitude->setPaperAttitude($objPaper->intId, $_POST['paper-'.$objPaper->intId]);
  }
  foreach ($objTopics as $objTopic) {
    $objReviewerAttitude->setTopicAttitude($objTopic->intId, $_POST['topic-'.$objTopic->intId]);
  }
  // Schreibe in die Datenbank
  $myDBAccess->updateReviewerAttitude($objReviewerAttitude);
  if ($myDBAccess->failed()) {
    error('insert new preference into database',$myDBAccess->getLastError());
  }
}*/

$content = new Template(TPLPATH.'chair_reviewerassignment.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['if'] = array();
$strContentAssocs['reviewer_lines'] = '';
if (!empty($r_id)) {
  foreach ($r_id as $rid) {
    $lineNo = 1;
    $objReviewer = $myDBAccess->getPerson($rid);
    if ($myDBAccess->failed()) {
      error('get reviewer data', $myDBAccess->getLastError());
    }
    $objReviewerAttitude = $myDBAccess->getReviewerAttitude($rid, session('confid'));
    if ($myDBAccess->failed()) {
      error('get reviewer attitude mapping', $myDBAccess->getLastError());
    }
    $strItemAssocs = defaultAssocArray();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['paper_id'] = encodeText($objPaper->intId);
    $strItemAssocs['rev_id'] = encodeText($objReviewer->intId);
    $strItemAssocs['rev_name'] = encodeText($objReviewer->getName(1));
    $strItemAssocs['if'] = array($objReviewerAttitude->getPaperAttitude($objPaper->intId));
    //$strItemAssocs['if'] = array($objReviewerAttitude->getTopicAttitude($objTopic->intId));
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
$strMainAssocs['title'] = 'Edit reviewers of paper \''.encodeText($objPaper->strTitle).'\'';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Manage reviews';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>