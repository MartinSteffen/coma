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
  $objAllTopics = $myDBAccess->getTopicsOfConference(session('confid'));
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
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
  $intCoAuthorNum = $_POST['coauthors_num'];
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
  $objPaper->objTopics = array();
  if (!empty($_POST['topics'])) {    
    echo($_POST['topics']);    
    for ($i = 0; $i < count($objAllTopics); $i++) {      
      $intTopicId = $objAllTopics[$i]->intId;
      if (isset('topic-'.$intTopicId)) {
        echo('Topic'.$objAllTopics[$i]->strName.','.$objAllTopics[$i]->intId);
      	$objPaper->objTopics[] = $objAllTopics[$i];      	
      }
    }
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
  else if (isset($_POST['delete'])) {    
    if (empty($_POST['confirm_delete'])) {
      $strMessage = 'You have to check the delete confirm option!';
    }
    // Versuche das Paper zu loeschen
    else {
      $result = $myDBAccess->deletePaper($objPaper);
      if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error during deleting paper.', $myDBAccess->getLastError());
      }
      else if (!empty($result)) {        
        $_SESSION['message'] = 'Paper was deleted successfully.';
        redirect("author_papers.php");
      }
    }
  }
  else if (isset($_POST['upload'])) {    
    if (empty($_POST['paper_file'])) {
      $strMessage = 'You have to select a file for uploading!';
    }
    // Versuche das Paper hochzuladen
    else {    	      
      $result = $myDBAccess->uploadPaperFile($objPaper->intId, $_POST['paper_file']);
      if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error during uploading paper.', $myDBAccess->getLastError());
      }
      else if (!empty($result)) {        
        $strMessage = 'Document was uploaded successfully.';        
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
  $coauthorForm = new Template(TPLPATH.'paper_coauthorlistitem.tpl');
  $strCoauthorAssocs = defaultAssocArray();
  $strCoauthorAssocs['coauthor_no'] = encodeText($i+1);
  $strCoauthorAssocs['coauthor']    = encodeText($objPaper->strCoAuthors[$i]);
  $strCoauthorAssocs['coauthor_id'] = encodeText($objPaper->intCoAuthorIds[$i]);
  $coauthorForm->assign($strCoauthorAssocs);
  $coauthorForm->parse();
  $strContentAssocs['coauthor_lines'] .= $coauthorForm->getOutput();
}
$strContentAssocs['topic_lines'] = '';
for ($i = 0; $i < count($objAllTopics); $i++) {
  $topicForm = new Template(TPLPATH.'paper_topiclistitem.tpl');
  $strTopicAssocs = defaultAssocArray();
  $strTopicAssocs['topic_id'] = encodeText($objAllTopics[$i]->intId);
  $strTopicAssocs['topic']    = encodeText($objAllTopics[$i]->strName);
  $strTopicAssocs['if'] = array();
  if ($objPaper->hasTopic($objAllTopics[$i]->intId)) {
    $strTopicAssocs['if'] = array(1);  	
  }
  $topicForm->assign($strTopicAssocs);
  $topicForm->parse();
  $strContentAssocs['topic_lines'] .= $topicForm->getOutput();
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
