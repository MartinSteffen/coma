<?php

//ACHTUNG!!! Das hier wird noch nicht funktionieren!

define('DEBUG',true);

require_once('./include/header.inc.php');
require_once('./include/class.forum.inc.php');
require_once('./include/class.message.inc.php');
require_once('./include/class.person.inc.php');

//Funktiondefinitionen

//Hilfsfunktion zum feststellen ob eine valide Auswahl vorliegt, iow: es ist ein nichtleeres array in dem mindestens einmal der Wert true steht
function validSelection($selectarray){
  if (empty($selectarray)){
    return false;
  }
  else{
    foreach ($selectarray as $selected){
      if ($selected){
        return true;
      }
    }
    return false;
  }
}

//Hilfsfunktion zum zusammenbauen des Template-Replacements des Forums
function buildForumtemplates($forums, $forumselection, $msgselection, $select, $assocArray){
  //Die Ordnung der Foren durch dreifaches durchlaufen der Schleifen ist schlecht, das kann noch verbessert werden;
  //ist fuer die Funktionalitaet aber noch nicht so wichtig
  $tempstring = '';
  $forumtypeopen = new Template(TPLPATH . 'forumtypes.tpl');
  $typeopenassocs = deflautAssocArray();
  $typeopenassocs['type'] = 'Open forums';
  foreach ($forums as $forum){
    if (isOpenForum($forum)){
      $forum = new Template(TPLPATH . 'forum.tpl');
      $forumassocs = defaultAssocArray();
      if ($forumselection[$forum->intId]){
        $forumassocs['selectorunselect'] = 'forumunsel';
        $forumassocs['forum-id'] = $forum->intId;
        $forumassocs['forum-title'] = $forum->strTitle;
        $forumassocs['plusorminus'] = '-';
        displayMessages(getThreadsOfForum($forum-intId), $msgselection, $select, $forumassocs);
      }
      else{
        $forumassocs['selectorunselect'] = 'forumsel';
        $forumassocs['forum-id'] = $forum->intId;
        $forumassocs['forum-title'] = $forum->strTitle;
        $forumassocs['plusorminus'] = '+';
        $forumassocs['messages'] = '';
      }
      //Thread-neu
      $threadtemplate = new Template(TPLPATH . 'messageform.tpl');
      $threadassocs = defaultAssocArray();
      $threadassocs['replystring'] = 'Start new thread';
      $threadassocs['message-id'] = '';
      $threadassocs['forum-id'] = $forum->intId;
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

      $forum->assign($forumassocs);
      $forum->parse();
      $tempstring = $tempstring . $forum->getOutput();
    }
  }
  if ($tempstring != ''){
    $typeopenassocs['forum'] = $typeopenassocs['forum'] . $tempstring;
  }
  else{
    $typeopenassocs['forum'] = $typeopenassocs['forum'] . 'No forums availabe in this category';
  }
  $forumtypeopen->assign($typeopenassocs);
  $forumtypeopen->parse();
  $assocArray['forumtypes'] = $assocArray['forumtypes'] . $forumtypeopen->getOutput();

  $tempstring = '';
  $forumtypepaper = new Template(TPLPATH . 'forumtypes.tpl');
  $typepaperassocs = deflautAssocArray();
  $typepaperassocs['type'] = 'Paper forums';
  foreach ($forums as $forum){
    if (isPaperForum($forum)){
      $forum = new Template(TPLPATH . 'forum.tpl');
      $forumassocs = defaultAssocArray();
      if ($forumselection[$forum->intId]){
        $forumassocs['selectorunselect'] = 'forumunsel';
        $forumassocs['forum-id'] = $forum->intId;
        $forumassocs['forum-title'] = $forum->strTitle;
        $forumassocs['plusorminus'] = '-';
        displayMessages(getThreadsOfForum($forum-intId), $msgselection, $select, $forumassocs);
      }
      else{
        $forumassocs['selectorunselect'] = 'forumsel';
        $forumassocs['forum-id'] = $forum->intId;
        $forumassocs['forum-title'] = $forum->strTitle;
        $forumassocs['plusorminus'] = '+';
        $forumassocs['messages'] = '';
      }
      //Thread-neu
      $threadtemplate = new Template(TPLPATH . 'messageform.tpl');
      $threadassocs = defaultAssocArray();
      $threadassocs['replystring'] = 'Start new thread';
      $threadassocs['message-id'] = '';
      $threadassocs['forum-id'] = $forum->intId;
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

      $forum->assign($forumassocs);
      $forum->parse();
      $tempstring = $tempstring . $forum->getOutput();
    }
  }
  if ($tempstring != ''){
    $typepaperassocs['forum'] = $typepaperassocs['forum'] . $tempstring;
  }
  else{
    $typepaperassocs['forum'] = $typepaperassocs['forum'] . 'No forums availabe in this category';
  }
  $forumtypepaper->assign($typepaperassocs);
  $forumtypepaper->parse();
  $assocArray['forumtypes'] = $assocArray['forumtypes'] . $forumtypepaper->getOutput();

  $tempstring = '';
  $forumtypechair = new Template(TPLPATH . 'forumtypes.tpl');
  $typechairassocs = deflautAssocArray();
  $typechairassocs['type'] = 'Chair forums';
  foreach ($forums as $forum){
    if (isChairForum($forum)){
      $forum = new Template(TPLPATH . 'forum.tpl');
      $forumassocs = defaultAssocArray();
      if ($forumselection[$forum->intId]){
        $forumassocs['selectorunselect'] = 'forumunsel';
        $forumassocs['forum-id'] = $forum->intId;
        $forumassocs['forum-title'] = $forum->strTitle;
        $forumassocs['plusorminus'] = '-';
        displayMessages(getThreadsOfForum($forum-intId), $msgselection, $select, $forumassocs);
      }
      else{
        $forumassocs['selectorunselect'] = 'forumsel';
        $forumassocs['forum-id'] = $forum->intId;
        $forumassocs['forum-title'] = $forum->strTitle;
        $forumassocs['plusorminus'] = '+';
        $forumassocs['messages'] = '';
      }
      //Thread-neu ist hier gemacht - bei Gelegenheit gleich alles in eine Schleife packen
      $threadtemplate = new Template(TPLPATH . 'messageform.tpl');
      $threadassocs = defaultAssocArray();
      $threadassocs['replystring'] = 'Start new thread';
      $threadassocs['message-id'] = '';
      $threadassocs['forum-id'] = $forum->intId;
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

      $forum->assign($forumassocs);
      $forum->parse();
      $tempstring = $tempstring . $forum->getOutput();
    }
  }
  if ($tempstring != ''){
    $typechairassocs['forum'] = $typechairassocs['forum'] . $tempstring;
  }
  else{
    $typechairassocs['forum'] = $typechairassocs['forum'] . 'No forums availabe in this category';
  }
  $forumtypechair->assign($typechairassocs);
  $forumtypechair->parse();
  $assocArray['forumtypes'] = $assocArray['forumtypes'] . $forumtypechair->getOutput();
}

function displayMessages($messages, $msgselection, $selected, $forumid, $assocs){
  $tempstring = '';
  foreach ($messages as $message){
    $messagetemplate = new Template(TPLPATH . 'message.tpl');
    $messageassocs = defaultAssocArray();
    $sender = getPerson($message->intSender);
    if ($msgselection[$message->intId]){
      $messageassocs['selectorunselect'] = 'unselect';
      $messageassocs['message-id'] = $message->intId;
      $messageassocs['plusorminus'] = '-';
      $messageassocs['sender-title'] = $sender->strTitle;
      $messageassocs['sender-firstname'] = $sender->strFirstName;
      $messageassocs['sender-lastname'] = $sender->strLastName;
      $messageassocs['message-subject'] = $message->strSubject;
      $messageassocs['message-sendtime'] = $message->strSendTime;
      $messageassocs['message-text'] = $message->strText;
      //formular anzeigen/aendern
      if ($message->intId == $selected){
	$formtemplate = new Template(TPLPATH . 'messageform.tpl');
	$formassocs = defaultAssocArray();
	$formassocs['message-id'] = $message->intId;
	$formassocs['forum-id'] = $forumid;
	$formassocs['subject'] = 'Re: ' . $message->strSubject;
	$formassocs['text'] = $message->strText;
	$formassocs['newthread'] = '';
        if (($sender->intId == $_SESSION['uid']) || (DEBUG) || (isChair(getPerson($_SESSION['uid'])))){
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
        displayMessages($message->getNextMessages(), $msgselection, $selected, $forumid, $messageassocs);
      }
    }
    else{
      $messageassocs['selectorunselect'] = 'select';
      $messageassocs['message-id'] = $message->intId;
      $messageassocs['plusorminus'] = '+';
      $messageassocs['sender-title'] = $sender->strTitle;
      $messageassocs['sender-firstname'] = $sender->strFirstName;
      $messageassocs['sender-lastname'] = $sender->strLastName;
      $messageassocs['message-subject'] = $message->strSubject;
      $messageassocs['message-sendtime'] = $message->strSendTime;
      $messageassocs['message-text'] = '';
      $messageassocs['edit-reply-form'] = '';
      $messageassocs['messages'] = '';
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


//Main-Code

if ((empty($_Session['uid'])) && (!DEBUG)){
  redirect('login.php');
}
else{
  
  $content = new Template(TPLPATH . 'forumtypes.tpl');
  $contentAssocs = defaultAssocArray();
  $contentAssocs['message'] = session('message', false);
  session_delete('message');
  
  
  if (DEBUG){
    $contentAssocs['message'] = $contentAssocs['message'] . '<br><h1>ACHTUNG! Forum ist im Debugmode. Das muss vor der Final-Version noch abgeschaltet werden!</h1>'
  }
  
  //foren holen
  if (DEBUG){
    $forums = getAllForums();
  }
  else{
    $forums = getForumsOfUser($_Session['uid']);
  }
  
  //selektionen updaten
  if (!empty($HTTP_GET_VARS['select'])){
    $tselect = $HTTP_GET_VARS['select'];
    $temp = $_SESSION['forum_msgselect'];
    $temp[$tselect] = true;
    $_SESSION['forum_msgselect'] = $temp;
  }
  if (!empty($HTTP_GET_VARS['unselect'])){
    $tunselect = $HTTP_GET_VARS['unselect'];
    if (!empty($_SESSION['forum_msgselect'])){
      $temp = $_SESSION['forum_msgselect'];
      $temp[$tunselect] = false;
      $_SESSION['forum_msgselect'] = $temp;
    }
  }
  if (!empty($HTTP_GET_VARS['forumsel'])){
    $tselect = $HTTP_GET_VARS['forumsel'];
    $temp = $_SESSION['forum_forumselect'];
    $temp[$tselect] = true;
    $_SESSION['forum_forumselect'] = $temp;
  }
  if (!empty($HTTP_GET_VARS['forumunsel'])){
    $tunselect = $HTTP_GET_VARS['forumunsel'];
    if (!empty($_SESSION['forum_forumselect'])){
      $temp = $_SESSION['forum_forumselect'];
      $temp[$tunselect] = false;
      $_SESSION['forum_forumselect'] = $temp;
    }
  }


  //TODO die Methoden die posten/anzeigen ausfuehren sollen muessen dringen geupdatet werden
  //TODO beachten ob ein neuer Thread angelegt wird und ob update/reply

  //veraltet
  if ((!empty($HTTP_POST_VARS['reply-to'])) && (!empty($HTTP_POST_VARS['text'])) && (validSelection($_SESSION['forum_msgselect']))){
    //posten
    if (($HTTP_POST_VARS['update'] == true) && (1 == 2)){  //noch keine update-funktion
      if (DEBUG){
        $postresult = addMessage('DEBUG ' . $HTTP_POST_VARS['subject'], $HTTP_POST_VARS['text'], $_SESSION['uid'], $HTTP_POST_VARS['forumid'], $HTTP_POST_VARS['reply-to']);
      }
      else{
        $postresult = addMessage($HTTP_POST_VARS['subject'], $HTTP_POST_VARS['text'], $_SESSION['uid'], $HTTP_POST_VARS['forumid'], $HTTP_POST_VARS['reply-to']);
      }
    }
    else{
      if (DEBUG){
        $postresult = addMessage('DEBUG ' . $HTTP_POST_VARS['subject'], $HTTP_POST_VARS['text'], $_SESSION['uid'], $HTTP_POST_VARS['forumid'], $HTTP_POST_VARS['reply-to']);
      }
      else{
        $postresult = addMessage($HTTP_POST_VARS['subject'], $HTTP_POST_VARS['text'], $_SESSION['uid'], $HTTP_POST_VARS['forumid'], $HTTP_POST_VARS['reply-to']);
      }
    }
    if ($postresult != false){
      $selecttree = $_SESSION['forum_msgselect'];
      $selecttree[$postresult] = true;
      $_SESSION['forum_msgselect'] = $selecttree;
      //jetzt den forumanzeigestring zusammenbauen
      $forumtemplate = $forumtemplate . buildForumtemplates($forums, $_SESSION['forum_forumselect'], $selecttree, $_SESSION['select'], $contentAssocs);
    }
    else{
      $contentAssocs['message'] = $contentAssocs['message'] . '<br>posting failed';
    }
  }

  //veraltet
  if ((empty($HTTP_GET_VARS['select'])) && (!validSelection($_SESSION['forum_msgselect']))){
    //forumsuebersicht, evtl. selektiertes forum anzeigen
    buildForumtemplates($forums, $_SESSION['forum_forumselect'], $_SESSION['forum_msgselect'], $_SESSION['select'], $contentAssocs);
  }

  //veraltet
  if (((empty($HTTP_POST_VARS['reply-to'])) && (!validSelection($_SESSION['forum_msgselect']))) || ((validSelection($_SESSION['forum_msgselect'])) && (!empty($HTTP_GET_VARS['select']))) || ((empty($HTTP_POST_VARS['text'])) && (!validSelection($_SESSION['forum_msgselect'])))){
    //select validieren und anzeigen
    buildForumtemplates($forums, $_SESSION['forum_forumselect'], $_SESSION['forum_msgselect'], $_SESSION['select'], $contentAssocs);
  }
  
  $content->assign($contentAssocs);
  $content->parse();
}
