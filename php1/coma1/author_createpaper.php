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
$ifArray = array();

// Lade die Daten des Autoren
$objAuthor = $myDBAccess->getPersonDetailed(session('uid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving author.', $myDBAccess->getLastError());
}
else if (empty($objAuthor)) {
  error('Author does not exist in database.', '');
}
$objAllTopics = $myDBAccess->getTopicsOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'create_paper.tpl');
$strContentAssocs = defaultAssocArray();

// Teste, ob Daten mit der Anfrage des Benutzer mitgeliefert wurde.
if (isset($_POST['action'])) {    
  $strContentAssocs['title']       = encodeText($_POST['title']);
  $strContentAssocs['abstract']    = encodeText($_POST['description']);
  $strContentAssocs['author_name'] = encodeText($objAuthor->getName());
  $intCoAuthorNum = $_POST['coauthors_num'];
  $strCoAuthors = array();
  for ($i = 0; $i < $intCoAuthorNum; $i++) {
    if (!isset($_POST['del_coauthor-'.($i+1)])) {
      $strCoAuthors[] = encodeText($_POST['coauthor-'.($i+1)]);      
    }
  }
  if (isset($_POST['add_coauthor']) && !empty($_POST['coauthor'])) {
    $strCoAuthors[] = encodeText($_POST['coauthor']);    
  }    
  $intTopicIds = array();
  for ($i = 0; $i < count($objAllTopics); $i++) {
    if (isset($_POST['topic-'.$objAllTopics[$i]->intId])) {
      $intTopicIds[] = $objAllTopics[$i]->intId;
    }
  }  
  $strContentAssocs['topic_lines'] = '';
  for ($i = 0; $i < count($objAllTopics); $i++) {
    $topicForm = new Template(TPLPATH.'paper_topiclistitem.tpl');
    $strTopicAssocs = defaultAssocArray();
    $strTopicAssocs['topic_id'] = encodeText($objAllTopics[$i]->intId);
    $strTopicAssocs['topic']    = encodeText($objAllTopics[$i]->strName);
    $strTopicAssocs['if'] = array();
    if (isset($_POST['topic-'.$objAllTopics[$i]->intId])) {
      $strTopicAssocs['if'] = array(1);  	
    }
    $topicForm->assign($strTopicAssocs);
    $topicForm->parse();
    $strContentAssocs['topic_lines'] .= $topicForm->getOutput();
  }
  // Anlegen des Papers in der Datenbank
  if (isset($_POST['submit'])) {
    // Teste, ob alle Pflichtfelder ausgefuellt wurden
    if (empty($_POST['title'])) {  
      $strMessage = 'You have to fill in the field <b>Title</b>!';
    }
    // Versuche einzutragen
    else {
      $result = $myDBAccess->addPaper(session('confid'), $objAuthor->intId,
                                      $_POST['title'], $_POST['description'],
                                      $strCoAuthors, $intTopicIds);                                     
      if (!empty($result)) {
        // Erfolg (kehre zurueck zur Artikelliste)
        $_SESSION['message'] = 'Paper was successfully created. '.
                               'Please upload the document file soon.';
        redirect('author_papers.php');
      }
      else if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error during creating new paper.', $myDBAccess->getLastError());
      }
    }
  }
}
else {
  $strContentAssocs['title']       = '';
  $strContentAssocs['abstract']    = '';  
  $intCoAuthorNum = 0;
  $strCoAuthors = array();
  $intTopicIds = array();
  $strContentAssocs['topic_lines'] = '';
  for ($i = 0; $i < count($objAllTopics); $i++) {
    $topicForm = new Template(TPLPATH.'paper_topiclistitem.tpl');
    $strTopicAssocs = defaultAssocArray();
    $strTopicAssocs['topic_id'] = encodeText($objAllTopics[$i]->intId);
    $strTopicAssocs['topic']    = encodeText($objAllTopics[$i]->strName);
    $strTopicAssocs['if'] = array();
    $topicForm->assign($strTopicAssocs);
    $topicForm->parse();
    $strContentAssocs['topic_lines'] .= $topicForm->getOutput();
  }
}

$strContentAssocs['author_name'] = encodeText($objAuthor->getName());
$strContentAssocs['coauthors_num'] = encodeText(count($strCoAuthors));
$strContentAssocs['coauthor_lines'] = '';
for ($i = 0; $i < count($strCoAuthors); $i++) {
  $coauthorForm = new Template(TPLPATH.'paper_coauthorlistitem.tpl');
  $strCoauthorAssocs = defaultAssocArray();
  $strCoauthorAssocs['coauthor_no'] = encodeText($i+1);
  $strCoauthorAssocs['coauthor']    = encodeText($strCoAuthors[$i]);    
  $strCoauthorAssocs['if'] = array();
  $coauthorForm->assign($strCoauthorAssocs);
  $coauthorForm->parse();
  $strContentAssocs['coauthor_lines'] .= $coauthorForm->getOutput();
}
$strContentAssocs['targetpage'] = 'author_createpaper.php';
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
$strMainAssocs['title'] = 'Add new paper';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Author  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>
