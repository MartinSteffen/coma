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

$content = new Template(TPLPATH.'reviewer_prefers.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['if'] = array();
$strContentAssocs['paper_lines'] = '';
if (!empty($objPapers)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
    $ifArray = array();
    $strItemAssocs = defaultAssocArray();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['title'] = encodeText($objPaper->strTitle);
    $strItemAssocs['paper_id'] = $objPaper->intId;
    $strItemAssocs['author_id'] = $objPaper->intAuthorId;
    $strItemAssocs['author_name'] = encodeText($objPaper->strAuthor);        
    $ifArray[] = $objPaper->intStatus;    
    $strItemAssocs['if'] = $ifArray;
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