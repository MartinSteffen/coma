<?php

define('DEBUG', true);
define('IN_COMA1', true);

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
  $forumtypeopen = new Template(TPLPATH . 'forumtypes.tpl');
  $typeopenassocs = defaultAssocArray();
  $typeopenassocs['type'] = 'Open forums';
  $forumtypepaper = new Template(TPLPATH . 'forumtypes.tpl');
  $typepaperassocs = defaultAssocArray();
  $typepaperassocs['type'] = 'Paper forums';
  $forumtypechair = new Template(TPLPATH . 'forumtypes.tpl');
  $typechairassocs = defaultAssocArray();
  $typechairassocs['type'] = 'Chair forums';
  $openforumtemplates = array();
  $paperforumtemplates = array();
  $chairforumtemplates = array();

  foreach ($forums as $forum){
    $forum = new Template(TPLPATH . 'forum.tpl');
    $forumassocs = defaultAssocArray();
    if ($forumselection[$forum->intId]){
      $forumassocs['selectorunselect'] = 'forumunsel';
      $forumassocs['forum-id'] = $forum->intId;
      $forumassocs['forum-title'] = $forum->strTitle;
      $forumassocs['plusorminus'] = '-';
      displayMessages($myDBAccess->getThreadsOfForum($forum-intId), $msgselection, $select, $forumassocs);
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
    if (isOpenForum($forum)){
      $openforumtemplates[$forum->intId] = $forum;
    }
    if (isPaperForum($forum)){
      $paperforumtemplates[$forum->intId] = $forum;
    }
    if (isChairForum($forum)){
      $chairforumtemplates[$forum->intId] = $forum;
    }
  }

  if (!empty($openforumtemplates)){
    foreach ($openforumtemplates as $ftemp){
      $typeopenassocs['forum'] = $typeopenassocs['forum'] . $ftemp->getOutput();
    }
  }
  else{
    $typeopenassocs['forum'] = 'No forums availabe in this category';
  }
  if (!empty($paperforumtemplates)){
    foreach ($paperforumtemplates as $ftemp){
      $typepaperassocs['forum'] = $typepaperassocs['forum'] . $ftemp->getOutput();
    }
  }
  else{
    $typepaperassocs['forum'] = $typepaperassocs['forum'] . 'No forums availabe in this category';
  }
  if (!empty($chairforumtemplates)){
    foreach ($chairforumtemplates as $ftemp){
      $typechairassocs['forum'] = $typechairassocs['forum'] . $ftemp->getOutput();
    }
  }
  else{
    $typechairassocs['forum'] = $typechairassocs['forum'] . 'No forums availabe in this category';
  }
  $forumtypeopen->assign($typeopenassocs);
  $forumtypeopen->parse();
  $assocArray['forumtypes'] = $assocArray['forumtypes'] . $forumtypeopen->getOutput();
  $forumtypepaper->assign($typepaperassocs);
  $forumtypepaper->parse();
  $assocArray['forumtypes'] = $assocArray['forumtypes'] . $forumtypepaper->getOutput();
  $forumtypechair->assign($typechairassocs);
  $forumtypechair->parse();
  $assocArray['forumtypes'] = $assocArray['forumtypes'] . $forumtypechair->getOutput();
}

function displayMessages($messages, $msgselection, $selected, $forumid, $assocs){
  $tempstring = '';
  foreach ($messages as $message){
    $messagetemplate = new Template(TPLPATH . 'message.tpl');
    $messageassocs = defaultAssocArray();
    $sender = $myDBAccess->getPerson($message->intSender);
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
      $messageassocs['colon'] = ':';
      $messageassocs['postfix'] = 'wrote:';
      //formular anzeigen/aendern
      if ($message->intId == $selected){
        $formtemplate = new Template(TPLPATH . 'messageform.tpl');
        $formassocs = defaultAssocArray();
        $formassocs['message-id'] = $message->intId;
        $formassocs['forum-id'] = $forumid;
        $formassocs['subject'] = 'Re: ' . $message->strSubject;
        $formassocs['text'] = $message->strText;
        $formassocs['newthread'] = '';
        if (($sender->intId == session('uid')) || (DEBUG) || (isChair($myDBAccess->getPerson(session('uid'))))){
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
      $messageassocs['sender-title'] = '';
      $messageassocs['sender-firstname'] = '';
      $messageassocs['sender-lastname'] = '';
      $messageassocs['message-subject'] = $message->strSubject;
      $messageassocs['message-sendtime'] = '';
      $messageassocs['message-text'] = '';
      $messageassocs['edit-reply-form'] = '';
      $messageassocs['messages'] = '';
      $messageassocs['colon'] = '';
      $messageassocs['postfix'] = '';
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

function generatePostMethodArray($postvars){
  $pma = array();
  if (empty($postvars['posttype'])){
    $pma['posttype'] = 'reply';
  }
  else{
    $pma['posttype'] = $postvars['posttype'];
  }
  $pma['reply-to'] = $postvars['reply-to'];
  $pma['text'] = $postvars['text'];
  $pma['subject'] = $postvars['subject'];
  $pma['uid'] = session('uid');
  $pma['forumid'] = $postvars['forumid'];
  return $pma;
}


//Main-Code

if ((empty(session('uid'))) && (!DEBUG)){
  redirect('login.php');
}
else{

  $content = new Template(TPLPATH . 'forumtypes.tpl');
  $contentAssocs = defaultAssocArray();
  $contentAssocs['message'] = session('message', false);
  session_delete('message');

  if (DEBUG){
    $contentAssocs['message'] = $contentAssocs['message'] . '<br><h1>ACHTUNG! Forum ist im Debugmode. Das muss vor der Final-Version noch abgeschaltet werden!</h1>';
  }

  //evtl. posten einleiten
  if ((!empty($HTTP_POST_VARS['reply-to'])) && (!empty($HTTP_POST_VARS['text']))){
    $pvars = generatePostMethodArray($HTTP_POST_VARS);
    $postresult = false;
    //auf einen Beitrag antworten
    if (($pvars['posttype'] == 'reply') && (!empty($pvars['text'])) && (!empty($pvars['forumid'])) && (!empty($pvars['reply-to']))){
        if (DEBUG){
          $postresult = $myDBAccess->addMessage('DEBUG ' . $pvars['subject'], $pvars['text'], 1, $pvars['forumid'], $pvars['reply-to']); //UserID 1 fuer maximale toleranz (wenn es den nicht gibt, gibt es auch keinen anderen)
        }
        else{
          if (!empty($pvars[uid])){
            $postresult = $myDBAccess->addMessage($pvars['subject'], $pvars['text'], $pvars['uid'], $pvars['forumid'], $pvars['reply-to']);
          }
          else{
            $postresult = false;
          }
        }
    }
    //einen Beitrag updaten - DBAccess Methode dazu fehlt noch
    if ((1 == 2) && ($pvars['posttype'] == 'update') && (!empty($pvars['reply-to'])) && (!empty($pvars['subject'])) && (!empty($pvars['text']))){
        if (DEBUG){
          $postresult = $myDBAccess->updateMessage('DEBUG ' . $pvars['subject'], $pvars['text'], 1, $pvars['forumid'], $pvars['reply-to']); //UserID 1 fuer maximale toleranz (wenn es den nicht gibt, gibt es auch keinen anderen)
        }
        else{
          if (!empty($pvars[uid])){
            $postresult = $myDBAccess->updateMessage($pvars['subject'], $pvars['text'], $pvars['uid'], $pvars['forumid'], $pvars['reply-to']);
          }
          else{
            $postresult = false;
          }
        }
    }
    //einen neuen Thread starten
    if (($pvars['posttype'] == 'newthread') && (!empty($pvars['text'])) && (!empty($pvars['forumid']))){
        if (DEBUG){
          $postresult = $myDBAccess->addMessage('DEBUG ' . $pvars['subject'], $pvars['text'], 1, $pvars['forumid'], 0); //UserID 1 fuer maximale toleranz (wenn es den nicht gibt, gibt es auch keinen anderen)
        }
        else{
          if (!empty($pvars[uid])){
            $postresult = $myDBAccess->addMessage($pvars['subject'], $pvars['text'], $pvars['uid'], $pvars['forumid'], 0);
          }
          else{
            $postresult = false;
          }
        }
    }

    if ($postresult != false){
      $selecttree = session('forum_msgselect');
      $selecttree[$postresult] = true;
      $_SESSION['forum_msgselect'] = $selecttree;
    }
    else{
      $contentAssocs['message'] = $contentAssocs['message'] . '<br>posting failed';
    }
  }

  //foren holen
  if (DEBUG){
    $forums = $myDBAccess->getAllForums(session('confid'));
  }
  else{
    $forums = $myDBAccess->getForumsOfPerson(session('uid'), session('confid'));
  }

  //selektionen updaten
  if (!empty($HTTP_GET_VARS['select'])){
    $tselect = $HTTP_GET_VARS['select'];
    $temp = session('forum_msgselect');
    $temp[$tselect] = true;
    $_SESSION['forum_msgselect'] = $temp;
  }
  if (!empty($HTTP_GET_VARS['unselect'])){
    $tunselect = $HTTP_GET_VARS['unselect'];
    if (!empty(session('forum_msgselect'))){
      $temp = session('forum_msgselect');
      $temp[$tunselect] = false;
      $_SESSION['forum_msgselect'] = $temp;
    }
  }
  if (!empty($HTTP_GET_VARS['forumsel'])){
    $tselect = $HTTP_GET_VARS['forumsel'];
    $temp = session('forum_forumselect');
    $temp[$tselect] = true;
    $_SESSION['forum_forumselect'] = $temp;
  }
  if (!empty($HTTP_GET_VARS['forumunsel'])){
    $tunselect = $HTTP_GET_VARS['forumunsel'];
    if (!empty(session('forum_forumselect'))){
      $temp = session('forum_forumselect');
      $temp[$tunselect] = false;
      $_SESSION['forum_forumselect'] = $temp;
    }
  }

  buildForumtemplates($forums, session('forum_forumselect'), session('forum_msgselect'), session('select'), $contentAssocs);

  $content->assign($contentAssocs);
  $content->parse();
  $content->output();
}

?>
