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
checkAccess(REVIEWER);

$objPapers = $myDBAccess->getPapersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get paper list',$myDBAccess->getLastError());
}
$objTopics = $myDBAccess->getTopicsOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get topic list',$myDBAccess->getLastError());
}
$objReviewerAttitude = $myDBAccess->getReviewerAttitude(session('uid'), session('confid'));
if ($myDBAccess->failed()) {
  error('get reviewer attitude mapping',$myDBAccess->getLastError());
}

if (isset($_POST['action']) && $_POST['action'] == 'submit') {
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
}

$content = new Template(TPLPATH.'reviewer_prefers.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['if'] = array();
$strContentAssocs['paper_lines'] = '';
if (!empty($objPapers)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
    $strItemAssocs = defaultAssocArray();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['title'] = encodeText($objPaper->strTitle);
    $strItemAssocs['paper_id'] = encodeText($objPaper->intId);
    $strItemAssocs['author_id'] = encodeText($objPaper->intAuthorId);
    $strItemAssocs['author_name'] = encodeText($objPaper->strAuthor);
    $strItemAssocs['if'] = array($objReviewerAttitude->getPaperAttitude($objPaper->intId));
    $paperItem = new Template(TPLPATH.'prefer_paperlistitem.tpl');
    $paperItem->assign($strItemAssocs);
    $paperItem->parse();
    $strContentAssocs['paper_lines'] .= $paperItem->getOutput();
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
$strContentAssocs['topic_lines'] = '';
if (!empty($objTopics)) {
  $lineNo = 1;
  foreach ($objTopics as $objTopic) {
    $strItemAssocs = defaultAssocArray();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['topic'] = encodeText($objTopic->strName);
    $strItemAssocs['topic_id'] = encodeText($objTopic->intId);
    $strItemAssocs['if'] = array($objReviewerAttitude->getTopicAttitude($objTopic->intId));
    $topicItem = new Template(TPLPATH.'prefer_topiclistitem.tpl');
    $topicItem->assign($strItemAssocs);
    $topicItem->parse();
    $strContentAssocs['topic_lines'] .= $topicItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {
  // Topicliste ist leer.
  $strItemAssocs = defaultAssocArray();
  $strItemAssocs['colspan'] = '4';
  $strItemAssocs['text'] = 'There are no topics available.';
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
$strContentAssocs['targetpage'] = 'reviewer_prefers.php';
$content->assign($strContentAssocs);

$_SESSION['menu'] = REVIEWER;
$_SESSION['menuitem'] = 3;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Edit preferences of reviewer '.encodeText(session('uname'));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Reviewer  |  Preferences';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>