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

//Achtung: Nur zu Testzwecken eingefuegt
require_once(INCPATH.'getCriticalPapers.inc.php');
require_once(INCPATH.'class.conferencedetailed.inc.php');
require_once(INCPATH.'class.papervariance.inc.php');
//bis hier

if (isset($_POST['action']) && $_POST['action'] == 'delete') {
  if (empty($_POST['confirm_delete'])) {
    $strMessage = 'You have to check the delete confirm option!';
  }
  else {
    $myDBAccess->deletePaper($_POST['paperid']);
    if ($myDBAccess->failed()) {
      error('Error deleting paper.', $myDBAccess->getLastError());
    }
  }
}

if (isset($_GET['order'])) {
  if ((int)session('orderpapers', false) != $_GET['order']) {
    $_SESSION['orderpapers'] = $_GET['order'];
  }
  else {
    unset($_SESSION['orderpapers']);
  }
}
$intOrder = (int)session('orderpapers', false);
$ifArray = array($intOrder);

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
$strContentAssocs['message'] = '';
$strMessage = session('message', false);
session_delete('message');
if (!empty($strMessage)) {  
  $strContentAssocs['message'] = $strMessage;
  $ifArray[] = 9;
}
$strContentAssocs['if'] = $ifArray;
$strContentAssocs['targetpage'] = 'chair_papers.php';
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
        $cpapstr = round($cpap->fltVariance * 100) . '%';
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

$_SESSION['menu'] = CHAIR;
$_SESSION['menuitem'] = 3;
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
