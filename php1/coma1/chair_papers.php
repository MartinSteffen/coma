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

//Achtung: Nur zu Testzwecken eingefuegt
include('./include/getCriticalPapers.inc.php');
require_once('./include/class.conferencedetailed.inc.php');
require_once('./include/class.papervariance.inc.php');
//bis hier

if (isset($_POST['action']) && $_POST['action'] == 'delete') {
  $myDBAccess->deletePaper($_POST['paperid']);
  if ($myDBAccess->failed()) {
    error('Error deleting paper.', $myDBAccess->getLastError());
  }
}

$objPapers = $myDBAccess->getPapersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get paper list of chair',$myDBAccess->getLastError());
}

//Achtung: Nur zu Testzwecken hier eingefuegt
$critPapers = getCriticalPapers($myDBAccess);
$confdet = $myDBAccess->getConferenceDetailed(session('confid'));
$critvar = $confdet->fltCriticalVariance;
//bis hier

$content = new Template(TPLPATH.'chair_paperlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['if'] = array();
$strContentAssocs['lines'] = '';
if (!empty($objPapers)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
    $strItemAssocs = defaultAssocArray();
    $ifArray = array();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['paper_id'] = encodeText($objPaper->intId);
    $strItemAssocs['author_id'] = encodeText($objPaper->intAuthorId);
    $strItemAssocs['author_name'] = encodeText($objPaper->strAuthor);      
    $ifArray[] = $objPaper->intStatus;
    if (!empty($objPaper->strFilePath)) {
      $ifArray[] = 5;
    }
    $strItemAssocs['title'] = encodeText($objPaper->strTitle);
    if (!empty($objPaper->fltAvgRating)) {
      $strItemAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 100).'%');
      //achtung: nur fuer testzwecke
      foreach($critPapers as $cpap){
        $cpapstr = round($cpap->fltVariance * 100);
	$cpapstr = $cpapstr . '%';
        if ($cpap->intId == $objPaper->intId){
	  if ($cpap->fltVariance > $critvar){
	    $strItemAssocs['variance'] = '!! ' . $cpapstr;
	  }
	  else{
	    $strItemAssocs['variance'] = $cpapstr;
	  }
	}
      }
      //bis hier
    }
    else {
      $strItemAssocs['avg_rating'] = ' - ';
      //achtung: nur fuer testzwecke
      $strItemAssocs['variance'] = ' - ';
      //bis hier
    }
    $strItemAssocs['last_edited'] = encodeText($objPaper->strLastEdit);
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
