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

$popup = (isset($_GET['popup'])) ? true : false;

// Lade die Daten des Benutzers
if (isset($_GET['userid']) || isset($_POST['userid'])) {
  $intPersonId = (isset($_GET['userid']) ? $_GET['userid'] : $_POST['userid']);
  $objPerson = $myDBAccess->getPersonDetailed($intPersonId, session('confid'));
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving person.', $myDBAccess->getLastError());
  }
  else if (empty($objPerson)) {
    error('Person '.$intPersonId.' does not exist in database.', '');
  }
  else if (!$objPerson->hasRole(REVIEWER)) {
    error('Person '.$intPersonId.' is no Reviewer.', '');
  }
}
else {
  error('No User selected!', '');
}

$objPapers = $myDBAccess->getPapersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get paper list',$myDBAccess->getLastError());
}
$objTopics = $myDBAccess->getTopicsOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get topic list',$myDBAccess->getLastError());
}
$objReviewerAttitude = $myDBAccess->getReviewerAttitude($intPersonId, session('confid'));
if ($myDBAccess->failed()) {
  error('get reviewer attitude mapping',$myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'chair_prefers.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['navlink']     = ($popup) ? array( 'CLOSE' ) : array( 'BACK' );
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
    $paperItem = new Template(TPLPATH.'chair_preferpaperlistitem.tpl');
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
    $topicItem = new Template(TPLPATH.'chair_prefertopiclistitem.tpl');
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
$content->assign($strContentAssocs);

include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'View preferences of reviewer '.encodeText($objPerson->getName(0));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Preferences';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>