<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage Forum
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);

require_once('./include/header.inc.php');
require_once(INCPATH.'class.forum.inc.php');
require_once(INCPATH.'class.message.inc.php');
require_once(INCPATH.'class.person.inc.php');

// Security :)
if (isset($_SESSION['confid'])) {
  checkAccess(0);
}

//Funktiondefinitionen

//Hilfsfunktion zum feststellen ob eine valide Auswahl vorliegt,
//iow: es ist ein nichtleeres array in dem mindestens einmal der Wert true steht
function validSelection($selectarray){
  // false = 0!
  return (array_sum($selectarray) > 0);
}

function notemptyandtrue($arr, $index){
  if (isset($arr[$index])) {
    return ($arr[$index] == true);
  }
  return false;
}

//Hilfsfunktion zum zusammenbauen des Template-Replacements des Forums
function buildForumtemplates(&$objArrayForums, $boolArrayForumselection, $boolArrayMsgselection, $intSelect, $assocArray, $intFshow){
  global $myDBAccess;

  $objForumTypeOpenTemplate = new Template(TPLPATH . 'forumtypes.tpl');
  $strArrayTypeOpenAssocs = defaultAssocArray();
  $strArrayTypeOpenAssocs['type'] = 'Public forums';
  /*
  if ((1 == 1) ||(isChair($myDBAccess->getPerson(session('uid'))))){
    $objCreateTemplate = new Template(TPLPATH . 'createforumform.tpl');
    $strArrayCreateAssocs = defaultAssocArray();
    $strArrayCreateAssocs['type'] = 'public';
    $strArrayCreateAssocs['inttype'] = FORUM_PUBLIC;
    $strArrayCreateAssocs['paperoptions'] = '';
    $objCreateTemplate->assign($strArrayCreateAssocs);
    $objCreateTemplate->parse();
    $strArrayTypeOpenAssocs['createforum'] = $objCreateTemplate->getOutput();
  }
  */
  $objForumTypePaperTemplate = new Template(TPLPATH . 'forumtypes.tpl');
  $strArrayTypePaperAssocs = defaultAssocArray();
  $strArrayTypePaperAssocs['type'] = 'Paper forums';
  /*
  if ((1 == 1) || (isChair($myDBAccess->getPerson(session('uid'))))){
    $objCreateTemplate = new Template(TPLPATH . 'createforumform.tpl');
    $strArrayCreateAssocs = defaultAssocArray();
    $strArrayCreateAssocs['type'] = 'paper';
    $strArrayCreateAssocs['inttype'] = FORUM_PAPER;
    $objArrayConfPapers = $myDBAccess->getPapersOfConference(session('confid'));
    if (!empty($objArrayConfPapers)){
      $strArrayCreateAssocs['if'] = array(1,2);
      $strArrayCreateAssocs['paperoptions'] = '';
      foreach ($objArrayConfPapers as $objPaper){
        $objOptTemplate = new Template(TPLPATH . 'paperoption.tpl');
	$strArrayOptAssocs = defaultAssocArray();
	$strArrayOptAssocs['paperid'] = $objPaper->intId;
	$strArrayOptAssocs['papertitle'] = $objPaper->strTitle;
	$objOptTemplate->assign($strArrayOptAssocs);
	$objOptTemplate->parse();
	$strArrayCreateAssocs['paperoptions'] = $strArrayCreateAssocs['paperoptions'] . $objOptTemplate->getOutput();
      }
    }
    $objCreateTemplate->assign($strArrayCreateAssocs);
    $objCreateTemplate->parse();
    $strArrayTypePaperAssocs['createforum'] = $objCreateTemplate->getOutput();
  }
  */
  $objForumTypeChairTemplate = new Template(TPLPATH . 'forumtypes.tpl');
  $strArrayTypeChairAssocs = defaultAssocArray();
  $strArrayTypeChairAssocs['type'] = 'Committee forums';
  /*
  if ((1 == 1) || (isChair($myDBAccess->getPerson(session('uid'))))){
    $objCreateTemplate = new Template(TPLPATH . 'createforumform.tpl');
    $strArrayCreateAssocs = defaultAssocArray();
    $strArrayCreateAssocs['type'] = 'committee';
    $strArrayCreateAssocs['inttype'] = FORUM_PRIVATE;
    $strArrayCreateAssocs['paperoptions'] = '';
    $objCreateTemplate->assign($strArrayCreateAssocs);
    $objCreateTemplate->parse();
    $strArrayTypeChairAssocs['createforum'] = $objCreateTemplate->getOutput();
  }
  */
  $objArrayOpenForumTemplates = array();
  $objArrayPaperForumTemplates = array();
  $objArrayChairForumTemplates = array();
  $strArrayTypeOpenAssocs['forum'] = '';
  $strArrayTypePaperAssocs['forum'] = '';
  $strArrayTypeChairAssocs['forum'] = '';

  foreach ($objArrayForums as $objForum){
    $objForumTemplate = new Template(TPLPATH . 'forum.tpl');
    $strArrayForumAssocs = defaultAssocArray();
    if (notemptyandtrue($boolArrayForumselection, $objForum->intId)){
      $strArrayForumAssocs['selectorunselect'] = 'forumunsel';
      $strArrayForumAssocs['forum-id'] = encodeText($objForum->intId);
      $strArrayForumAssocs['forum-title'] = encodeText($objForum->strTitle);
      $strArrayForumAssocs['plusorminus'] = '--';
      $objArrayMesses = $myDBAccess->getThreadsOfForum($objForum->intId);
      if ($myDBAccess->failed()){
        error('An error occurred while trying to retrieve the forum threads from the database.', $myDBAccess->getLastError());
      }
      $strArrayForumAssocs = displayMessages($objArrayMesses, $boolArrayMsgselection, $intSelect, $objForum->intId, $strArrayForumAssocs);
      //Thread-neu
      $objThreadtemplate = new Template(TPLPATH . 'messageform.tpl');
      $strArrayThreadAssocs = defaultAssocArray();
      $strArrayThreadAssocs['replystring'] = 'Start new thread';
      $strArrayThreadAssocs['message-id'] = '';
      $strArrayThreadAssocs['forum-id'] = encodeText($objForum->intId);
      $strArrayThreadAssocs['subject'] = '';
      $strArrayThreadAssocs['text'] = '';
      $objEdittemplate = new Template(TPLPATH . 'threadform.tpl');
      $strArrayEditAssocs = defaultAssocArray();
      $objEdittemplate->assign($strArrayEditAssocs);
      $objEdittemplate->parse();
      $strArrayThreadAssocs['editform'] = $objEdittemplate->getOutput();
      $objThreadtemplate->assign($strArrayThreadAssocs);
      $objThreadtemplate->parse();
      $strArrayForumAssocs['thread-new'] = $objThreadtemplate->getOutput();
    }
    else{
      $strArrayForumAssocs['selectorunselect'] = 'forumsel';
      $strArrayForumAssocs['forum-id'] = encodeText($objForum->intId);
      $strArrayForumAssocs['forum-title'] = encodeText($objForum->strTitle);
      $strArrayForumAssocs['plusorminus'] = '++';
      $strArrayForumAssocs['messages'] = '';
      $strArrayForumAssocs['thread-new'] = '';
    }

    $objForumTemplate->assign($strArrayForumAssocs);
    $objForumTemplate->parse();
    if (isOpenForum($objForum)){
      $objArrayOpenForumTemplates[$objForum->intId] = $objForumTemplate;
    }
    if (isPaperForum($objForum)){
      $objArrayPaperForumTemplates[$objForum->intId] = $objForumTemplate;
    }
    if (isChairForum($objForum)){
      $objArrayChairForumTemplates[$objForum->intId] = $objForumTemplate;
    }
  }

  if (!empty($objArrayOpenForumTemplates)){
    foreach ($objArrayOpenForumTemplates as $ftemp){
      $strArrayTypeOpenAssocs['forum'] = $strArrayTypeOpenAssocs['forum'] . $ftemp->getOutput();
    }
  }
  else{
    $strArrayTypeOpenAssocs['forum'] = 'No forums available in this category';
  }
  if (!empty($objArrayPaperForumTemplates)){
    foreach ($objArrayPaperForumTemplates as $ftemp){
      $strArrayTypePaperAssocs['forum'] = $strArrayTypePaperAssocs['forum'] . $ftemp->getOutput();
    }
  }
  else{
    $strArrayTypePaperAssocs['forum'] = 'No forums available in this category';
  }
  if (!empty($objArrayChairForumTemplates)){
    foreach ($objArrayChairForumTemplates as $ftemp){
      $strArrayTypeChairAssocs['forum'] = $strArrayTypeChairAssocs['forum'] . $ftemp->getOutput();
    }
  }
  else{
    $strArrayTypeChairAssocs['forum'] = 'No forums available in this category';
  }
  $objForumTypeOpenTemplate->assign($strArrayTypeOpenAssocs);
  $objForumTypeOpenTemplate->parse();
  if (($intFshow == 0) || ($intFshow == FORUM_PUBLIC)){
    $assocArray['forumtypes'] = $assocArray['forumtypes'] . $objForumTypeOpenTemplate->getOutput();
  }
  $objForumTypePaperTemplate->assign($strArrayTypePaperAssocs);
  $objForumTypePaperTemplate->parse();
  if (($intFshow == 0) || ($intFshow == FORUM_PAPER)){
    $assocArray['forumtypes'] = $assocArray['forumtypes'] . $objForumTypePaperTemplate->getOutput();
  }
  $objForumTypeChairTemplate->assign($strArrayTypeChairAssocs);
  $objForumTypeChairTemplate->parse();
  if (($intFshow == 0) || ($intFshow == FORUM_PRIVATE)){
    $assocArray['forumtypes'] = $assocArray['forumtypes'] . $objForumTypeChairTemplate->getOutput();
  }
  return $assocArray;
}

function displayMessages(&$objArrayMessages, $boolArrayMsgselection, $intSelected, $intForumid, $assocs){
  global $myDBAccess;

  $tempstring = '';
  foreach ($objArrayMessages as $objMessage){
    $objMessagetemplate = new Template(TPLPATH . 'message.tpl');
    $strArrayMessageAssocs = defaultAssocArray();
    $sender = $myDBAccess->getPerson($objMessage->intSender);
    if ($myDBAccess->failed()){
      error('An error occurred while trying to retrieve the sender of a forum message from the database. This could be a database inconsistency.', $myDBAccess->getLastError());
    }
    if (notemptyandtrue($boolArrayMsgselection, $objMessage->intId)){
      $strArrayMessageAssocs['selectorunselect'] = 'unselect';
      $strArrayMessageAssocs['message-id'] = encodeText($objMessage->intId);
      $strArrayMessageAssocs['plusorminus'] = '--';
      $strArrayMessageAssocs['sender-title'] = encodeText($sender->strTitle);
      $strArrayMessageAssocs['sender-firstname'] = encodeText($sender->strFirstName);
      $strArrayMessageAssocs['sender-lastname'] = encodeText($sender->strLastName);
      $strArrayMessageAssocs['message-subject'] = encodeText($objMessage->strSubject);
      $strArrayMessageAssocs['message-sendtime'] = encodeText($objMessage->strSendTime);
      $strArrayMessageAssocs['message-text'] = encodeText($objMessage->strText);
      $strArrayMessageAssocs['colon'] = ':';
      $strArrayMessageAssocs['postfix'] = 'wrote:';
      //formular anzeigen/aendern
      if ($objMessage->intId == $intSelected){
        $objFormtemplate = new Template(TPLPATH . 'messageform.tpl');
        $strArrayFormAssocs = defaultAssocArray();
        $strArrayFormAssocs['message-id'] = encodeText($objMessage->intId);
        $strArrayFormAssocs['forum-id'] = encodeText($intForumid);
        $strArrayFormAssocs['subject'] = 'Re: ' . encodeText($objMessage->strSubject);
        $strArrayFormAssocs['text'] = encodeText($objMessage->strText, false);
        $strArrayFormAssocs['newthread'] = '';
        $boolIschair = (isChair($myDBAccess->getPerson(session('uid'))));
        if ($myDBAccess->failed()){
          error('An error occurred while trying to retrieve your user status from the database. This could be a database inconsistency.', $myDBAccess->getLastError());
        }
        if (($sender->intId == session('uid')) || $boolIschair){
          //neu/aendern
          $strArrayFormAssocs['replystring'] = 'Update this message/Post a reply to this message';
          $objEdittemplate = new Template(TPLPATH . 'editform.tpl');
          $strArrayEditAssocs = defaultAssocArray();
          $objEdittemplate->assign($strArrayEditAssocs);
          $objEdittemplate->parse();
          $strArrayFormAssocs['editform'] = $objEdittemplate->getOutput();
        }
        else{
          //neu
          $strArrayFormAssocs['replystring'] = 'Post a reply to this message';
          $strArrayFormAssocs['editform'] = '';
        }
        $objFormtemplate->assign($strArrayFormAssocs);
        $objFormtemplate->parse();
        $strArrayMessageAssocs['edit-reply-form'] = $objFormtemplate->getOutput();
      }
      $objArrayMesses = $objMessage->getNextMessages();
      $strArrayMessageAssocs = displayMessages($objArrayMesses, $boolArrayMsgselection, $intSelected, $intForumid, $strArrayMessageAssocs);
    }
    else{
      $strArrayMessageAssocs['selectorunselect'] = 'select';
      $strArrayMessageAssocs['message-id'] = encodeText($objMessage->intId);
      $strArrayMessageAssocs['plusorminus'] = '++';
      $strArrayMessageAssocs['sender-title'] = '';
      $strArrayMessageAssocs['sender-firstname'] = '';
      $strArrayMessageAssocs['sender-lastname'] = '';
      $strArrayMessageAssocs['message-subject'] = encodeText($objMessage->strSubject);
      $strArrayMessageAssocs['message-sendtime'] = '';
      $strArrayMessageAssocs['message-text'] = '';
      $strArrayMessageAssocs['edit-reply-form'] = '';
      $strArrayMessageAssocs['messages'] = '';
      $strArrayMessageAssocs['colon'] = '';
      $strArrayMessageAssocs['postfix'] = '';
    }
    $objMessagetemplate->assign($strArrayMessageAssocs);
    $objMessagetemplate->parse();
    $tempstring = $tempstring . $objMessagetemplate->getOutput();
  }
  if ($tempstring != ''){
    $assocs['messages'] = $tempstring;
  }
  else{
    $assocs['messages'] = '';
  }
  return $assocs;
}

function isChair($objPerson){
  return ($objPerson->hasRole(CHAIR));
}

function isOpenForum($objForum){
  return ($objForum->intForumType == FORUM_PUBLIC);
}

function isPaperForum($objForum){
  return ($objForum->intForumType == FORUM_PAPER);
}

function isChairForum($objForum){
  return ($objForum->intForumType == FORUM_PRIVATE);
}

function isGlobalForum($objForum){
  return ($objForum->intForumType == FORUM_GLOBAL);
}

function generatePostMethodArray($strArrayPostvars){
  $strArrayPma = array();
  if (isset($strArrayPostvars['newthread'])){
    $strArrayPma['posttype'] = 'newthread';
  }
  if (isset($strArrayPostvars['update'])){
    $strArrayPma['posttype'] = 'update';
  }
  if ((isset($strArrayPostvars['reply'])) && (!isset($strArrayPostvars['newthread']))){
    $strArrayPma['posttype'] = 'reply';
  }
  $strArrayPma['reply-to'] = $strArrayPostvars['reply-to']; //wenn geupdated wird, dann ist reply-to gleich der id der Message die geupdated werden soll
  $strArrayPma['text']     = $strArrayPostvars['text'];
  if (!empty($strArrayPostvars['subject'])){
    $strArrayPma['subject'] = $strArrayPostvars['subject'];
  }
  else{
    $strArrayPma['subject'] = 'no subject';
  }
  $strArrayPma['forumid']  = $strArrayPostvars['forumid'];
  return $strArrayPma;
}


// Main-Code

  $objContenttemplate = new Template(TPLPATH . 'forumlist.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $strArrayContentAssocs['message'] = session('message', false);
  session_delete('message');
  $strArrayContentAssocs['forumtypes'] = '';

  //evtl. neues Forum erzeugen
  if ((!empty($_POST['title'])) && (!empty($_POST['type'])) && (!empty($_POST['createforum']))){
    if ((($_POST['type'] == FORUM_PAPER) && (empty($_POST['paperid']))) == false){
      if (empty($_POST['paperid'])){
        $pid = false;
      }
      else{
        $pid = $_POST['paperid'];
      }
      $intId = $myDBAccess->addForum(session('confid'), $_POST['title'], $_POST['type'], $pid);
      if ($myDBAccess->failed()) {
        error('creating new forum', $myDBAccess->getLastError());
      }
    }
  }

  //evtl. posten einleiten
  if ((isset($_POST['reply'])) || (isset($_POST['newthread'])) || (isset($_POST['update']))){
    $strArrayPvars = generatePostMethodArray($_POST);
    $intPostresult = false;
    //auf einen Beitrag antworten
    if (($strArrayPvars['posttype'] == 'reply') && (!empty($strArrayPvars['forumid'])) && (!empty($strArrayPvars['reply-to']))){
      $intPostresult = $myDBAccess->addMessage($strArrayPvars['subject'], $strArrayPvars['text'], session('uid'), $strArrayPvars['forumid'], $strArrayPvars['reply-to']);
    }
    //einen Beitrag updaten
    if (($strArrayPvars['posttype'] == 'update') && (!empty($strArrayPvars['reply-to'])) && (!empty($strArrayPvars['subject']))){
      $intPostresult = $myDBAccess->updateMessage(new Message($strArrayPvars['reply-to'], session('uid'), '', $strArrayPvars['subject'], $strArrayPvars['text'], null));
    }
    //einen neuen Thread starten
    if (($strArrayPvars['posttype'] == 'newthread') && (!empty($strArrayPvars['forumid']))){
      $intPostresult = $myDBAccess->addMessage($strArrayPvars['subject'], $strArrayPvars['text'], session('uid'), $strArrayPvars['forumid']);
    }
    // hat geklappt :)
    if (!empty($intPostresult)){
      $boolArraySelecttree = session('forum_msgselect', false);
      if (empty($boolArraySelecttree)){
        $boolArraySelecttree = array();
      }
      $boolArraySelecttree[$intPostresult] = true;
      $_SESSION['forum_msgselect'] = $boolArraySelecttree;
    }
    else{
      // posten fehlgeschlagen
      if ($myDBAccess->failed()){
        error('An error occurred while trying to post your forum message.', $myDBAccess->getLastError());
      }
    }
  }

  // Foren holen
  $objArrayForums = $myDBAccess->getForumsOfPerson(session('uid'), session('confid'));
  if ($myDBAccess->failed()) {
    error('Error getting forum list.', $myDBAccess->getLastError());
  }
  if (empty($objArrayForums)){
    $objArrayForums = array();
  }

  // Selektionen updaten
  $temp = session('forum_msgselect', false);
  if (empty($temp)){
    $temp = array();
  }
  if (!empty($_GET['select'])){
    $temp[$_GET['select']] = true;
  }
  if (!empty($_GET['unselect'])){
    $temp[$_GET['unselect']] = false;
  }
  $_SESSION['forum_msgselect'] = $temp;

  $temp = session('forum_forumselect', false);
  if (empty($temp)){
    $temp = array();
  }
  if (!empty($_GET['forumsel'])){
    $temp[$_GET['forumsel']] = true;
  }
  if (!empty($_GET['forumunsel'])){
    $temp[$_GET['forumunsel']] = false;
  }
  $_SESSION['forum_forumselect'] = $temp;

  if (isset($_GET['showforums'])){
    $temp = $_GET['showforums'];
    if (($temp >= 0) && ($temp <= 3)){
      $_SESSION['showforums'] = $temp;
    }
  }
  else{
    $temp = session('showforums', false);
    if (empty($temp)){
      $_SESSION['showforums'] = 0;
    }
  }

  $ffs = session('forum_forumselect', false);
  if (empty($ffs)){
    $ffs = array();
  }

  $fms = session('forum_msgselect', false);
  if (empty($fms)){
    $fms = array();
  }

  $sel = '';
  if (!empty($_GET['select'])){
    $sel = $_GET['select'];
  }

  $fshow = session('showforums', false);
  if (empty($fshow)){
    $fshow = 0;
  }

  $strArrayContentAssocs = buildForumtemplates($objArrayForums, $ffs, $fms, $sel, $strArrayContentAssocs, $fshow);
  $objContenttemplate->assign($strArrayContentAssocs);

  $_SESSION['menu'] = 0;  
  $_SESSION['menuitem'] = 10 + $fshow;  
  include(INCPATH.'usermenu.inc.php');

  $objMaintemplate = new Template(TPLPATH . 'frame.tpl');
  $strArrayMainAssocs = defaultAssocArray();
  $strArrayMainAssocs['title'] = 'Forums of ' . encodeText(session('uname'));
  $strArrayMainAssocs['content'] = &$objContenttemplate;
  $strArrayMainAssocs['menu'] = &$menu;
  $strArrayMainAssocs['navigator'] = encodeText(session('uname')) . '  |  Forums';

  $objMaintemplate->assign($strArrayMainAssocs);
  $objMaintemplate->parse();
  $objMaintemplate->output();
?>
