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
  $objForumTypePaperTemplate = new Template(TPLPATH . 'forumtypes.tpl');
  $strArrayTypePaperAssocs = defaultAssocArray();
  $strArrayTypePaperAssocs['type'] = 'Paper forums';
  $objForumTypeChairTemplate = new Template(TPLPATH . 'forumtypes.tpl');
  $strArrayTypeChairAssocs = defaultAssocArray();
  $strArrayTypeChairAssocs['type'] = 'Committee forums';
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
      $strArrayForumAssocs['plusorminus'] = '-';
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
      $strArrayForumAssocs['plusorminus'] = '+';
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
  if (($intFshow == 0) || ($intFshow == 1)){
    $assocArray['forumtypes'] = $assocArray['forumtypes'] . $objForumTypeOpenTemplate->getOutput();
  }
  $objForumTypePaperTemplate->assign($strArrayTypePaperAssocs);
  $objForumTypePaperTemplate->parse();
  if (($intFshow == 0) || ($intFshow == 3)){
    $assocArray['forumtypes'] = $assocArray['forumtypes'] . $objForumTypePaperTemplate->getOutput();
  }
  $objForumTypeChairTemplate->assign($strArrayTypeChairAssocs);
  $objForumTypeChairTemplate->parse();
  if (($intFshow == 0) || ($intFshow == 2)){
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
      $strArrayMessageAssocs['plusorminus'] = '-';
      $strArrayMessageAssocs['sender-title'] = encodeText($sender->strTitle);
      $strArrayMessageAssocs['sender-firstname'] = encodeText($sender->strFirstName);
      $strArrayMessageAssocs['sender-lastname'] = encodeText($sender->strLastName);
      $strArrayMessageAssocs['message-subject'] = encodeText($objMessage->strSubject);
      $strArrayMessageAssocs['message-sendtime'] = encodeText($objMessage->strSendTime);
      $strArrayMessageAssocs['message-text'] = encodeText($objMessage->strText);
      $strArrayMessageAssocs['colon'] = ':';
      $strArrayMessageAssocs['postfix'] = 'wrote:';
      $objReplylinktemplate = new Template(TPLPATH . 'message_replylink.tpl');
      $strArrayReplyAssocs = defaultAssocArray();
      $replylinkassocs['message-id'] = encodeText($objMessage->intId);
      $objReplylinktemplate->assign($replylinkassocs);
      $objReplylinktemplate->parse();
      $strArrayMessageAssocs['replylink'] = $objReplylinktemplate->getOutput();
      //formular anzeigen/aendern
      if ($objMessage->intId == $intSelected){
        $strArrayMessageAssocs['replylink'] = '';
        $objFormtemplate = new Template(TPLPATH . 'messageform.tpl');
        $strArrayFormAssocs = defaultAssocArray();
        $strArrayFormAssocs['message-id'] = encodeText($objMessage->intId);
        $strArrayFormAssocs['forum-id'] = encodeText($intForumid);
        $strArrayFormAssocs['subject'] = 'Re: ' . encodeText($objMessage->strSubject);
        $strArrayFormAssocs['text'] = encodeText($objMessage->strText);
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
      $strArrayMessageAssocs['plusorminus'] = '+';
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
      $objReplylinktemplate = new Template(TPLPATH . 'message_replylink.tpl');
      $strArrayReplyAssocs = defaultAssocArray();
      $strArrayReplyAssocs['message-id'] = encodeText($objMessage->intId);
      $objReplylinktemplate->assign($strArrayReplyAssocs);
      $objReplylinktemplate->parse();
      $strArrayMessageAssocs['replylink'] = $objReplylinktemplate->getOutput();
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
  return (($objPerson->intRoles == 1) || ($objPerson->intRoles == 2));
}

function isOpenForum($objForum){
  return ($objForum->intForumType == 1);
}

function isPaperForum($objForum){
  return ($objForum->intForumType == 3);
}

function isChairForum($objForum){
  return ($objForum->intForumType == 2);
}

function generatePostMethodArray($strArrayPostvars){
  $strArrayPma = array();
  if (empty($strArrayPostvars['posttype'])){
    $strArrayPma['posttype'] = 'reply';
  }
  else{
    $strArrayPma['posttype'] = $strArrayPostvars['posttype'];
  }
  $strArrayPma['reply-to'] = $strArrayPostvars['reply-to'];
  $strArrayPma['text']     = $strArrayPostvars['text'];
  $strArrayPma['subject']  = $strArrayPostvars['subject'];
  $strArrayPma['forumid']  = $strArrayPostvars['forumid'];
  return $strArrayPma;
}


// Main-Code

  /*
  if (DEBUG){
    echo('<h1>BEGIN VARDUMP $_POST</h1><br>');
    var_dump($_POST);
    echo('<h1>END VARDUMP $_POST</h1><br>');
    echo('<h1>BEGIN VARDUMP $HTTP_GET_VARS</h1><br>');
    var_dump($HTTP_GET_VARS);
    echo('<h1>END VARDUMP $HTTP_GET_VARS</h1><br>');
  }
  */

  $objContenttemplate = new Template(TPLPATH . 'forumlist.tpl');
  $strArrayContentAssocs = defaultAssocArray();
  $strArrayContentAssocs['message'] = session('message', false);
  session_delete('message');
  $strArrayContentAssocs['forumtypes'] = '';

  //evtl. posten einleiten
  if (((!empty($_POST['reply-to'])) || ((!empty($_POST['posttype'])) && ($_POST['posttype'] == 'newthread'))) && (!empty($_POST['text']))){
    $strArrayPvars = generatePostMethodArray($_POST);
    $intPostresult = false;
    //auf einen Beitrag antworten
    if (($strArrayPvars['posttype'] == 'reply') && (!empty($strArrayPvars['text'])) && (!empty($strArrayPvars['forumid'])) && (!empty($strArrayPvars['reply-to']))){
      $intPostresult = $myDBAccess->addMessage($strArrayPvars['subject'], $strArrayPvars['text'], $uid, $strArrayPvars['forumid'], $strArrayPvars['reply-to']);
    }
    /** @TODO einen Beitrag updaten - DBAccess Methode dazu fehlt noch*/
    //if (($strArrayPvars['posttype'] == 'update') && (!empty($strArrayPvars['reply-to'])) && (!empty($strArrayPvars['subject'])) && (!empty($strArrayPvars['text']))){
    //  $intPostresult = $myDBAccess->updateMessage($strArrayPvars['subject'], $strArrayPvars['text'], $uid, $strArrayPvars['forumid'], $strArrayPvars['reply-to']);
    //}
    //einen neuen Thread starten
    if (($strArrayPvars['posttype'] == 'newthread') && (!empty($strArrayPvars['text'])) && (!empty($strArrayPvars['forumid']))){
      $intPostresult = $myDBAccess->addMessage($strArrayPvars['subject'], $strArrayPvars['text'], $uid, $strArrayPvars['forumid']);
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
  $objArrayForums = $myDBAccess->getForumsOfPerson(session('uid'), session('confid', false));
  if ($myDBAccess->failed()) {
    error('Error getting forum list.', $myDBAccess->getLastError());
  }
  if (empty($objArrayForums)){
    $objArrayForums = array();
  }

  // selektionen updaten
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

  if (!empty($_GET['showforums'])){
    $temp = $_GET['showforums'];
    if (($temp >= 0) && ($temp <= 3)){
      $_SESSION['showforums'] = $temp;
    }
  }
  else{
    $_SESSION['showforums'] = 0;
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

  include(INCPATH.'usermenu.inc.php');

  $objMaintemplate = new Template(TPLPATH . 'frame.tpl');
  $strArrayMainAssocs = defaultAssocArray();
  $strArrayMainAssocs['title'] = 'Forums of ' . encodeText(session('uname', false));
  $strArrayMainAssocs['content'] = &$objContenttemplate;
  $strArrayMainAssocs['menu'] = &$menu;
  $strArrayMainAssocs['navigator'] = encodeText(session('uname', false)) . '  |  Forums';

  $objMaintemplate->assign($strArrayMainAssocs);
  $objMaintemplate->parse();
  $objMaintemplate->output();
?>
