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

$objPapers = $myDBAccess->getPapersOfAuthor(session('uid'), session('confid'));
if ($myDBAccess->failed()) {
  error('get paper list of author',$myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'author_paperlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = '';
$strContentAssocs['if'] = array();
$strContentAssocs['lines'] = '';
if (!empty($objPapers)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
    $ifArray = array();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['paper_id'] = $objPaper->intId;
    $strItemAssocs['file_link'] = encodeURL($objPaper->strFilePath);
    $ifArray[] = $objPaper->intStatus;
    if (!empty($objPaper->strFilePath)) {
      $ifArray[] = 5;
    }
    $strItemAssocs['title'] = encodeText($objPaper->strTitle);
    $strItemAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 100).'%');    
    $strItemAssocs['last_edited'] = encodeText(emptytime(strtotime($objPaper->strLastEdit)));
    $strItemAssocs['if'] = $ifArray;
    $paperItem = new Template(TPLPATH.'author_paperlistitem.tpl');
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

$strMessage = session('message', false);
unset($_SESSION['message']);
if (!empty($strMessage)) {  
  $strContentAssocs['message'] = encodeText($strMessage);
  $strContentAssocs['if'] = array(9);  
}
$content->assign($strContentAssocs);

$actMenu = AUTHOR;
$actMenuItem = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Manage papers of author '.encodeText(session('uname'));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Author  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>
