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
function buildForumtemplates(&$forums, $forumselection, $msgselection, $select, $assocArray, $fshow){
  global $myDBAccess;
  
  $forumtypeopen = new Template(TPLPATH . 'forumtypes.tpl');
  $typeopenassocs = defaultAssocArray();
  $typeopenassocs['type'] = 'Public forums';
  $forumtypepaper = new Template(TPLPATH . 'forumtypes.tpl');
  $typepaperassocs = defaultAssocArray();
  $typepaperassocs['type'] = 'Paper forums';
  $forumtypechair = new Template(TPLPATH . 'forumtypes.tpl');
  $typechairassocs = defaultAssocArray();
  $typechairassocs['type'] = 'Committee forums';
  $openforumtemplates = array();
  $paperforumtemplates = array();
  $chairforumtemplates = array();
  $typeopenassocs['forum'] = '';
  $typepaperassocs['forum'] = '';
  $typechairassocs['forum'] = '';

  $tempstring = '';
  foreach ($forums as $forum){
    $forumtemplate = new Template(TPLPATH . 'forum.tpl');
    $forumassocs = defaultAssocArray();
    if (notemptyandtrue($forumselection, $forum->intId)){
      $forumassocs['selectorunselect'] = 'forumunsel';
      $forumassocs['forum-id'] = encodeText($forum->intId);
      $forumassocs['forum-title'] = encodeText($forum->strTitle);
      $forumassocs['plusorminus'] = '-';
      $messes = $myDBAccess->getThreadsOfForum($forum->intId);
      if ($myDBAccess->failed()){
        error('An error occurred while trying to retrieve the forum threads from the database.', $myDBAccess->getLastError());
      }
      $forumassocs = displayMessages($messes, $msgselection, $select, $forum->intId, $forumassocs);
      //Thread-neu
      $threadtemplate = new Template(TPLPATH . 'messageform.tpl');
      $threadassocs = defaultAssocArray();
      $threadassocs['replystring'] = 'Start new thread';
      $threadassocs['message-id'] = '';
      $threadassocs['forum-id'] = encodeText($forum->intId);
      $threadassocs['subject'] = '';
      $threadassocs['text'] = '';
      $edittemplate = new Template(TPLPATH . 'threadform.tpl');
      $editassocs = defaultAssocArray();
      $edittemplate->assign($editassocs);
      $edittemplate->parse();
      $threadassocs['editform'] = $edittemplate->getOutput();
      $threadtemplate->assign($threadassocs);
      $threadtemplate->parse();
      $forumassocs['thread-new'] = $threadtemplate->getOutput();
    }
    else{
      $forumassocs['selectorunselect'] = 'forumsel';
      $forumassocs['forum-id'] = encodeText($forum->intId);
      $forumassocs['forum-title'] = encodeText($forum->strTitle);
      $forumassocs['plusorminus'] = '+';
      $forumassocs['messages'] = '';
      $forumassocs['thread-new'] = '';
    }

    $forumtemplate->assign($forumassocs);
    $forumtemplate->parse();
    $tempstring = $tempstring . $forumtemplate->getOutput();
    if (isOpenForum($forum)){
      $openforumtemplates[$forum->intId] = $forumtemplate;
    }
    if (isPaperForum($forum)){
      $paperforumtemplates[$forum->intId] = $forumtemplate;
    }
    if (isChairForum($forum)){
      $chairforumtemplates[$forum->intId] = $forumtemplate;
    }
  }

  if (!empty($openforumtemplates)){
    foreach ($openforumtemplates as $ftemp){
      $typeopenassocs['forum'] = $typeopenassocs['forum'] . $ftemp->getOutput();
    }
  }
  else{
    $typeopenassocs['forum'] = 'No forums available in this category';
  }
  if (!empty($paperforumtemplates)){
    foreach ($paperforumtemplates as $ftemp){
      $typepaperassocs['forum'] = $typepaperassocs['forum'] . $ftemp->getOutput();
    }
  }
  else{
    $typepaperassocs['forum'] = 'No forums available in this category';
  }
  if (!empty($chairforumtemplates)){
    foreach ($chairforumtemplates as $ftemp){
      $typechairassocs['forum'] = $typechairassocs['forum'] . $ftemp->getOutput();
    }
  }
  else{
    $typechairassocs['forum'] = 'No forums available in this category';
  }
  $forumtypeopen->assign($typeopenassocs);
  $forumtypeopen->parse();
  if (($fshow == 0) || ($fshow == 1)){
    $assocArray['forumtypes'] = $assocArray['forumtypes'] . $forumtypeopen->getOutput();
  }
  $forumtypepaper->assign($typepaperassocs);
  $forumtypepaper->parse();
  if (($fshow == 0) || ($fshow == 3)){
    $assocArray['forumtypes'] = $assocArray['forumtypes'] . $forumtypepaper->getOutput();
  }
  $forumtypechair->assign($typechairassocs);
  $forumtypechair->parse();
  if (($fshow == 0) || ($fshow == 2)){
    $assocArray['forumtypes'] = $assocArray['forumtypes'] . $forumtypechair->getOutput();
  }
  return $assocArray;
}

function displayMessages(&$messages, $msgselection, $selected, $forumid, $assocs){
  global $myDBAccess;

  $tempstring = '';
  foreach ($messages as $message){
    $messagetemplate = new Template(TPLPATH . 'message.tpl');
    $messageassocs = defaultAssocArray();
    $sender = $myDBAccess->getPerson($message->intSender);
    if ($myDBAccess->failed()){
      error('An error occurred while trying to retrieve the sender of a forum message from the database. This could be a database inconsistency.', $myDBAccess->getLastError());
    }
    if (notemptyandtrue($msgselection, $message->intId)){
      $messageassocs['selectorunselect'] = 'unselect';
      $messageassocs['message-id'] = encodeText($message->intId);
      $messageassocs['plusorminus'] = '-';
      $messageassocs['sender-title'] = encodeText($sender->strTitle);
      $messageassocs['sender-firstname'] = encodeText($sender->strFirstName);
      $messageassocs['sender-lastname'] = encodeText($sender->strLastName);
      $messageassocs['message-subject'] = encodeText($message->strSubject);
      $messageassocs['message-sendtime'] = encodeText($message->strSendTime);
      $messageassocs['message-text'] = encodeText($message->strText);
      $messageassocs['colon'] = ':';
      $messageassocs['postfix'] = 'wrote:';
      $replylinktemplate = new Template(TPLPATH . 'message_replylink.tpl');
      $replyassocs = defaultAssocArray();
      $replylinkassocs['message-id'] = encodeText($message->intId);
      $replylinktemplate->assign($replylinkassocs);
      $replylinktemplate->parse();
      $messageassocs['replylink'] = $replylinktemplate->getOutput();
      //formular anzeigen/aendern
      if ($message->intId == $selected){
        $messageassocs['replylink'] = '';
        $formtemplate = new Template(TPLPATH . 'messageform.tpl');
        $formassocs = defaultAssocArray();
        $formassocs['message-id'] = encodeText($message->intId);
        $formassocs['forum-id'] = encodeText($forumid);
        $formassocs['subject'] = 'Re: ' . encodeText($message->strSubject);
        $formassocs['text'] = encodeText($message->strText);
        $formassocs['newthread'] = '';
        $ischair = (isChair($myDBAccess->getPerson(session('uid'))));
        if ($myDBAccess->failed()){
          error('An error occurred while trying to retrieve your user status from the database. This could be a database inconsistency.', $myDBAccess->getLastError());
        }
        if (($sender->intId == session('uid')) || $ischair){
          //neu/aendern
          $formassocs['replystring'] = 'Update this message/Post a reply to this message';
          $edittemplate = new Template(TPLPATH . 'editform.tpl');
          $editassocs = defaultAssocArray();
          $edittemplate->assign($editassocs);
          $edittemplate->parse();
          $formassocs['editform'] = $edittemplate->getOutput();
        }
        else{
          //neu
          $formassocs['replystring'] = 'Post a reply to this message';
          $formassocs['editform'] = '';
        }
        $formtemplate->assign($formassocs);
        $formtemplate->parse();
        $messageassocs['edit-reply-form'] = $formtemplate->getOutput();
      }
      $messes = $message->getNextMessages();
      $messageassocs = displayMessages($messes, $msgselection, $selected, $forumid, $messageassocs);
    }
    else{
      $messageassocs['selectorunselect'] = 'select';
      $messageassocs['message-id'] = encodeText($message->intId);
      $messageassocs['plusorminus'] = '+';
      $messageassocs['sender-title'] = '';
      $messageassocs['sender-firstname'] = '';
      $messageassocs['sender-lastname'] = '';
      $messageassocs['message-subject'] = encodeText($message->strSubject);
      $messageassocs['message-sendtime'] = '';
      $messageassocs['message-text'] = '';
      $messageassocs['edit-reply-form'] = '';
      $messageassocs['messages'] = '';
      $messageassocs['colon'] = '';
      $messageassocs['postfix'] = '';
      $replylinktemplate = new Template(TPLPATH . 'message_replylink.tpl');
      $replyassocs = defaultAssocArray();
      $replylinkassocs['message-id'] = encodeText($message->intId);
      $replylinktemplate->assign($replylinkassocs);
      $replylinktemplate->parse();
      $messageassocs['replylink'] = $replylinktemplate->getOutput();
    }
    $messagetemplate->assign($messageassocs);
    $messagetemplate->parse();
    $tempstring = $tempstring . $messagetemplate->getOutput();
  }
  if ($tempstring != ''){
    $assocs['messages'] = $tempstring;
  }
  else{
    $assocs['messages'] = '';
  }
  return $assocs;
}

function isChair($person){
  return (($person->intRoles == 1) || ($person->intRoles == 2));
}

function isOpenForum($forum){
  return ($forum->intForumType == 1);
}

function isPaperForum($forum){
  return ($forum->intForumType == 3);
}

function isChairForum($forum){
  return ($forum->intForumType == 2);
}

function generatePostMethodArray($postvars){
  $pma = array();
  if (empty($postvars['posttype'])){
    $pma['posttype'] = 'reply';
  }
  else{
    $pma['posttype'] = $postvars['posttype'];
  }
  $pma['reply-to'] = $postvars['reply-to'];
  $pma['text']     = $postvars['text'];
  $pma['subject']  = $postvars['subject'];
  $pma['forumid']  = $postvars['forumid'];
  return $pma;
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

  $content = new Template(TPLPATH . 'forumlist.tpl');
  $contentAssocs = defaultAssocArray();
  $contentAssocs['message'] = session('message', false);
  session_delete('message');
  $contentAssocs['forumtypes'] = '';

  //evtl. posten einleiten
  if (((!empty($_POST['reply-to'])) || ((!empty($_POST['posttype'])) && ($_POST['posttype'] == 'newthread'))) && (!empty($_POST['text']))){
    $pvars = generatePostMethodArray($_POST);
    $postresult = false;
    //auf einen Beitrag antworten
    if (($pvars['posttype'] == 'reply') && (!empty($pvars['text'])) && (!empty($pvars['forumid'])) && (!empty($pvars['reply-to']))){
      $postresult = $myDBAccess->addMessage($pvars['subject'], $pvars['text'], $uid, $pvars['forumid'], $pvars['reply-to']);
    }
    // TODO
    //einen Beitrag updaten - DBAccess Methode dazu fehlt noch
    //if (($pvars['posttype'] == 'update') && (!empty($pvars['reply-to'])) && (!empty($pvars['subject'])) && (!empty($pvars['text']))){
    //  $postresult = $myDBAccess->updateMessage($pvars['subject'], $pvars['text'], $uid, $pvars['forumid'], $pvars['reply-to']);
    //}
    //einen neuen Thread starten
    if (($pvars['posttype'] == 'newthread') && (!empty($pvars['text'])) && (!empty($pvars['forumid']))){
      $postresult = $myDBAccess->addMessage($pvars['subject'], $pvars['text'], $uid, $pvars['forumid']);
    }
    // hat geklappt :)
    if (!empty($postresult)){
      $selecttree = session('forum_msgselect', false);
      if (empty($selecttree)){
        $selecttree = array();
      }
      $selecttree[$postresult] = true;
      $_SESSION['forum_msgselect'] = $selecttree;
    }
    else{
      // posten fehlgeschlagen
      if ($myDBAccess->failed()){
        error('An error occurred while trying to post your forum message.', $myDBAccess->getLastError());
      }
    }
  }

  // Foren holen
  $forums = $myDBAccess->getForumsOfPerson(session('uid'), session('confid', false));
  if ($myDBAccess->failed()) {
    error('Error getting forum list.', $myDBAccess->getLastError());
  }
  if (empty($forums)){
    $forums = array();
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

  $contentAssocs = buildForumtemplates($forums, $ffs, $fms, $sel, $contentAssocs, $fshow);
  $content->assign($contentAssocs);

  include(INCPATH.'usermenu.inc.php');

  $main = new Template(TPLPATH . 'frame.tpl');
  $mainassocs = defaultAssocArray();
  $mainassocs['title'] = 'Forums of ' . encodeText(session('uname', false));
  $mainassocs['content'] = &$content;
  $mainassocs['menu'] = &$menu;
  $mainassocs['navigator'] = encodeText(session('uname', false)) . '  |  Forums';

  $main->assign($mainassocs);
  $main->parse();
  $main->output();
?>
