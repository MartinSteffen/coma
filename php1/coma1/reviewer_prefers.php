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
    $strItemAssocs['paper_id'] = $objPaper->intId;
    $strItemAssocs['author_id'] = $objPaper->intAuthorId;
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
}
$strContentAssocs['topic_lines'] = '';
if (!empty($objTopic)) {
  $lineNo = 1;
  foreach ($objTopics as $objTopic) {    
    $strItemAssocs = defaultAssocArray();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['topic'] = encodeText($objTopic->strName);
    $strItemAssocs['topic_id'] = $objTopic->intId;    
    $strItemAssocs['if'] = array($objReviewerAttitude->getTopicAttitude($objTopic->intId));
    $paperItem = new Template(TPLPATH.'prefer_topiclistitem.tpl');
    $paperItem->assign($strItemAssocs);
    $paperItem->parse();
    $strContentAssocs['topic_lines'] .= $paperItem->getOutput();    
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {
  // Topicliste ist leer.
}
$content->assign($strContentAssocs);

$actMenu = REVIEWER;
$actMenuItem = 3;
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