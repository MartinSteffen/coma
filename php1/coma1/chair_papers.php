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

$content = new Template(TPLPATH.'chair_paperlist.tpl');
$strContentAssocs = defaultAssocArray();

$objPapers = $myDBAccess->getPapersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get paper list',$myDBAccess->getLastError());
}

$strContentAssocs['message'] = session('message', false);
session_delete('message');
$strContentAssocs['if'] = array();
$strContentAssocs['lines'] = '';
if (!empty($objConferences)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['paper_id'] = $objPaper->intId;
    $strItemAssocs['author_id'] = $objPaper->intAuthorId;
    $strItemAssocs['author_name'] = encodeText($objPaper->strAuthor);      
    $strItemAssocs['file_link'] = encodeURL($objPaper->strFilePath);
    $ifArray[] = $objPaper->intStatus;
    if (!empty($objPaper->strFilePath)) {
      $ifArray[] = 5;
    }
    $strItemAssocs['title'] = encodeText($objPaper->strTitle);
    $strItemAssocs['avg_rating'] = encodeText($objPaper->fltAvgRating);
    $strItemAssocs['last_edited'] = 'TODO';        
    $strItemAssocs['if'] = $ifArray;
    $paperItem = new Template(TPLPATH.'chair_paperlistitem.tpl');
    $paperItem->assign($strItemAssocs);
    $paperItem->parse();
    $strContentAssocs['lines'] .= $paperItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {
  // Artikelliste ist leer.
}

$content->assign($strContentAssocs);

$actMenu = CHAIR;
$actMenuItem = 3;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Manage papers';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>