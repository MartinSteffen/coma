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
checkAccess(AUTHOR);

// Lade die Daten des Artikels
if (isset($_GET['paperid']) || isset($_POST['paperid'])) {
  $intPaperId = (isset($_GET['paperid']) ? $_GET['paperid'] : $_POST['paperid']);
  // Pruefe, ob das Paper zur aktuellen Konferenz gehoert
  checkPaper($intPaperId);
  $objPaper = $myDBAccess->getPaperDetailed($intPaperId);
  if ($myDBAccess->failed()) {
    error('get paper details', $myDBAccess->getLastError());
  }
  else if (empty($objPaper)) {
    error('get paper details','Paper '.$intPaperId.' does not exist in database.');
  }
  $objAllTopics = $myDBAccess->getTopicsOfConference(session('confid'));
  if ($myDBAccess->failed()) {
    error('gather conference topics', $myDBAccess->getLastError());
  }
}
else {
  redirect('author_papers.php');
}

// Pruefe ob die Paper-Deadline erreicht worden ist
$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get conference details',$myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('get conference details','Conference '.session('confid').' does not exist in database.');
}
if (strtotime($objConference->strFinalDeadline) <= strtotime("now")) {
  $strMessage = 'Final version deadline has already been reached. You can\'t change information about the paper anymore.';
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
  // Sammle die Liste aller Coautoren aus den entsprechenden Feldern zusammen
  for ($i = 0; $i < $intCoAuthorNum; $i++) {
    // Fuege Coautor hinzu, wenn er nicht gerade geloescht wurde
    if (!isset($_POST['del_coauthor-'.($i+1)])) {
      $objPaper->strCoAuthors[] = $_POST['coauthor-'.($i+1)];
      $objPaper->intCoAuthorIds[] = false;
    }
  }
  // Fuege neuen Coautor hinzu, wenn einer eingetragen wurde
  if (/*isset($_POST['add_coauthor']) &&*/ !empty($_POST['coauthor'])) {
    $objPaper->strCoAuthors[] = $_POST['coauthor'];
    $objPaper->intCoAuthorIds[] = false;
  }
  // Sammle alle angekreuzten Topics zusammen
  $objPaper->objTopics = array();
  for ($i = 0; $i < count($objAllTopics); $i++) {
    if (isset($_POST['topic-'.$objAllTopics[$i]->intId])) {
      $objPaper->objTopics[] = $objAllTopics[$i];
    }
  }
  // Paper aktualisieren
  if (isset($_POST['submit']) &&
      (strtotime($objConference->strFinalDeadline) > strtotime("now"))) {     

    // Teste, ob alle Pflichtfelder ausgefuellt wurden
    if (empty($_POST['title'])) {
      $strMessage = 'You have to fill in the field <b>Title</b>!';
    }
    // Versuche das Paper zu aktualisieren
    else {
      $result = $myDBAccess->updatePaper($objPaper);
      if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('updating paper', $myDBAccess->getLastError());
      }
      else if (!empty($result)) {
        $objPaper->strLastEdit = date('r');
        $strMessage = 'Paper was updated successfully.';
      }
    }
  }
  // Paper loeschen
  else if (isset($_POST['delete']) &&
           $objPaper->intStatus == PAPER_ACCEPTED) {
    // Teste, ob alle Pflichtfelder ausgefuellt wurden
    if (empty($_POST['confirm_delete'])) {
      $strMessage = 'You have to check the delete confirm option!';
    }
    // Versuche das Paper zu loeschen
    else {
      $result = $myDBAccess->deletePaper($objPaper->intId);
      if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('deleting paper', $myDBAccess->getLastError());
      }
      else if (!empty($result)) {
        $_SESSION['message'] = 'Paper was deleted successfully.';
        redirect("author_papers.php");
      }
    }
  }
  // Datei hochladen
  else if (isset($_POST['upload']) &&
           (strtotime($objConference->strFinalDeadline) > strtotime("now"))) {     
    // Teste, ob alle Pflichtfelder ausgefuellt wurden
    if (!isset($_FILES['userfile']) ||
        !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
      $strMessage = 'You have to select a correct file for uploading!';
    }
    // Versuche das Paper hochzuladen
    else {
      $tmp = $_FILES['userfile']['tmp_name'];
      $filehandle = fopen($tmp, "rb") or error('Upload File', 'Can\'t read the file!');
      $file = fread($filehandle, filesize($tmp));
      fclose ($filehandle);
      $result = $myDBAccess->uploadPaperFile($objPaper->intId,
                                             $_FILES['userfile']['name'],
                                             $_FILES['userfile']['type'],
                                             $_FILES['userfile']['size'],
                                             $file);
      unset($file);
      if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('uploading paper', $myDBAccess->getLastError());
      }
      else if (!empty($result)) {
        $objPaper->intVersion++;
        $strMessage = 'Document was uploaded successfully.';
      }
    }
  }
}
$strContentAssocs['targetpage']     = 'author_editpaper.php';
$strContentAssocs['paper_id']       = encodeText($objPaper->intId);
$strContentAssocs['title']          = encodeText($objPaper->strTitle);
$strContentAssocs['abstract']       = encodeText($objPaper->strAbstract, false);
$strContentAssocs['author_id']      = encodeText($objPaper->intAuthorId);
$strContentAssocs['author_name']    = encodeText($objPaper->strAuthor);
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
  $strCoauthorAssocs['if'] = array(1);
  $coauthorForm->assign($strCoauthorAssocs);
  $coauthorForm->parse();
  $strContentAssocs['coauthor_lines'] .= $coauthorForm->getOutput();
}

if (empty($objAllTopics)) {
  $strContentAssocs['topic_lines'] = 'none';
}
else {
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
}
if ($objPaper->intStatus == PAPER_ACCEPTED) {
  $ifArray[] = 2;
}
else {
  $ifArray[] = 1;
}
// Pruefe ob die Final-Deadline noch nicht erreicht wurde
if (strtotime("now") < strtotime($objConference->strFinalDeadline)) {
  $ifArray[] = 3;
}

$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $ifArray[] = 9;
}
$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

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
