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

// Lade die Daten des Artikels
if (isset($_GET['paperid']) || isset($_POST['paperid'])) {
  $intPaperId = (isset($_GET['paperid']) ? $_GET['paperid'] : $_POST['paperid']);
  $objPaper = $myDBAccess->getPaperDetailed($intPaperId);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving paper.', $myDBAccess->getLastError());
  }
  else if (empty($objPaper)) {
    error('Paper does not exist in database.', '');
  }
}
else {
  redirect('author_papers.php');
}

$content = new Template(TPLPATH.'edit_paper.tpl');
$strContentAssocs = defaultAssocArray();
$ifArray = array();
//$ifArray[] = $objPaper->intStatus;
if (isset($_POST['action'])) {  
  $objPaper->strTitle = $_POST['title'];
  $objPaper->strAbstract = $_POST['description'];
  $intCoAuthorNum = $_POST['coauthors_num']
  $objPaper->strCoAuthors = array();
  $objPaper->intCoAuthorIds = array();
  for ($i = 0; $i < $intCoAuthorNum; $i++) {
    if (!isset($_POST['del_coauthor-'.($i+1)])) {
      $objPaper->strCoAuthors[] = encodeText($_POST['coauthor-'.($i+1)]);
      $objPaper->intCoAuthorIds[] = false;
    }
  }
  if (isset($_POST['add_coauthor']) && !empty($_POST['coauthor'])) {
    $objPaper->strCoAuthors[] = encodeText($_POST['coauthor']);
    $objPaper->intCoAuthorIds[] = false;
  }    
  if (isset($_POST['submit'])) {
    // Teste, ob alle Pflichtfelder ausgefuellt wurden
    if (empty($_POST['title'])) {
      $strMessage = 'You have to fill in the field <b>Title</b>!';
    }
    // Versuche das Paper zu aktualisieren
    else {
      $result = $myDBAccess->updatePaper($objPaper);
      if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error during updating paper.', $myDBAccess->getLastError());
      }
      else if (!empty($result)) {        
        $strMessage = 'Paper was updated successfully.';
      }
    }
  }
}
$strContentAssocs['targetpage']     = 'author_editpaper.php';
$strContentAssocs['paper_id']       = encodeText($objPaper->intId);
$strContentAssocs['title']          = encodeText($objPaper->strTitle);
$strContentAssocs['abstract']       = encodeText($objPaper->strAbstract);
$strContentAssocs['author_id']      = encodeText($objPaper->intAuthorId);
$strContentAssocs['author_name']    = encodeText($objPaper->strAuthor);      
$strContentAssocs['file_link']      = encodeURL($objPaper->strFilePath);
$strContentAssocs['avg_rating']     = encodeText(round($objPaper->fltAvgRating * 10) / 10);
$strContentAssocs['last_edited']    = encodeText($objPaper->strLastEdit);
$strContentAssocs['version']        = encodeText($objPaper->intVersion);
$strContentAssocs['coauthors_num']  = encodeText(count($objPaper->strCoAuthors));
$strContentAssocs['coauthor_lines'] = '';
for ($i = 0; $i < count($objPaper->strCoAuthors); $i++) {
  $coauthorForm = new Template(TPLPATH.'coauthor_listitem.tpl');
  $strCoauthorAssocs = defaultAssocArray();
  $strCoauthorAssocs['coauthor_no'] = encodeText($i+1);
  $strCoauthorAssocs['coauthor']    = encodeText($objPaper->strCoAuthors[$i]);
  $strCoauthorAssocs['coauthor_id'] = encodeText($objPaper->intCoAuthorIds[$i]);
  $coauthorForm->assign($strCoauthorAssocs);
  $coauthorForm->parse();
  $strContentAssocs['coauthor_lines'] .= $coauthorForm->getOutput();
}
$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $ifArray[] = 9;
}
$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$actMenu = AUTHOR;
$actMenuItem = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Edit paper';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Author  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>
