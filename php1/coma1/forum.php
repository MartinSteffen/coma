<?php
/**
 * @version $Id: apply_reviewer.php 2515 2005-01-20 08:10:46Z esquivel $
 * @package coma1
 * @subpackage forum
 */
/***/

/*** @ignore */
define('DEBUG', true);
/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);

include_once('./include/header.inc.php');
include_once('./include/class.forum.inc.php');
include_once('./include/class.message.inc.php');
include_once('./include/class.person.inc.php');

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

function notemptyandtrue($arr, $index){
  if (empty($arr)){
    return false;
  }
  else{
    if (empty($arr[$index])){
      return false;
    }
    else{
      return ($arr[$index] == true);
    }
  }
}

//Hilfsfunktion zum zusammenbauen des Template-Replacements des Forums
function buildForumtemplates($forums, $forumselection, $msgselection, $select, $assocArray){
  if (DEBUG){
    echo('forums: ' . count($forums));
  }
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
  $typeopenassocs['forum'] = '';
  $typepaperassocs['forum'] = '';
  $typechairassocs['forum'] = '';

  $tempstring = '';
  foreach ($forums as $forum){
    $forumtemplate = new Template(TPLPATH . 'forum.tpl');
    $forumassocs = defaultAssocArray();
    if (notemptyandtrue($forumselection, $forum->intId)){
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
  if (DEBUG){
    echo($assocArray['forumtypes']);
  }
}

function displayMessages($messages, $msgselection, $selected, $forumid, $assocs){
  $tempstring = '';
  foreach ($messages as $message){
    if (DEBUG){
      echo('Messages: ' . count($messages));
    }
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
        if (($sender->intId == getUID(getCID())) || (DEBUG) || (isChair($myDBAccess->getPerson(getUID(getCID()))))){
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
  $pma['forumid'] = $postvars['forumid'];
  return $pma;
}

function emptystring($s){
  return ($s == '');
}

function getUID($cid, $myDBAccess){
  $uid = session('uid', false);
  if (emptystring($uid)){
    if (DEBUG){ //ja, sehr haesslich, weiss ich selbst
      $users = $myDBAccess->getUsersOfConference($cid);
      srand ((double)microtime()*1000000);
      $randval = rand(0,count($users)-1);
      $uid = $users[$randval]->intId;
      $_SESSION['uid'] = $uid;
    }
    else{
      $uid = session('uid');
    }
  }
  return $uid;
}

function getCID($myDBAccess){
  $cid = session('confid', false);
  if (emptystring($cid)){
    if (DEBUG){ //siehe debug-kommentar zu getUID
      $confs = $myDBAccess->getAllConferences();
      srand ((double)microtime()*1000000);
      $randval = rand(0,count($confs)-1);
      $cid = $confs[$randval]->intId;
      $_SESSION['confid'] = $cid;
    }
    else{
      $cid = session('confid');
    }
  }
  return $cid;
}

//Main-Code

if ((emptystring(session('uid', false))) && (!DEBUG)){
  redirect('login.php');
}
else{

  $cid = getCID($myDBAccess);
  $uid = getUID($cid, $myDBAccess);

  $content = new Template(TPLPATH . 'forumtypes.tpl');
  $contentAssocs = defaultAssocArray();
  $contentAssocs['message'] = session('message', false);
  session_delete('message');
  $contentAssocs['forumtypes'] = '';

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
          $postresult = $myDBAccess->addMessage('DEBUG ' . $pvars['subject'], $pvars['text'], $uid, $pvars['forumid'], $pvars['reply-to']);
        }
        else{
          $postresult = $myDBAccess->addMessage($pvars['subject'], $pvars['text'], $uid, $pvars['forumid'], $pvars['reply-to']);
        }
    }
    //einen Beitrag updaten - DBAccess Methode dazu fehlt noch
    if ((1 == 2) && ($pvars['posttype'] == 'update') && (!empty($pvars['reply-to'])) && (!empty($pvars['subject'])) && (!empty($pvars['text']))){
        if (DEBUG){
          $postresult = $myDBAccess->updateMessage('DEBUG ' . $pvars['subject'], $pvars['text'], $uid, $pvars['forumid'], $pvars['reply-to']);
        }
        else{
          $postresult = $myDBAccess->updateMessage($pvars['subject'], $pvars['text'], $uid, $pvars['forumid'], $pvars['reply-to']);
        }
    }
    //einen neuen Thread starten
    if (($pvars['posttype'] == 'newthread') && (!empty($pvars['text'])) && (!empty($pvars['forumid']))){
        if (DEBUG){
          $postresult = $myDBAccess->addMessage('DEBUG ' . $pvars['subject'], $pvars['text'], $uid, $pvars['forumid'], 0);
        }
        else{
          $postresult = $myDBAccess->addMessage($pvars['subject'], $pvars['text'], $uid, $pvars['forumid'], 0);
        }
    }

    if ($postresult != false){
      $selecttree = session('forum_msgselect', false);
      if (!emptystring($selecttree)){
        $selecttree[$postresult] = true;
      }
      else{
        $selecttree = array();
	$selecttree[$postresult] = true;
      }
      $_SESSION['forum_msgselect'] = $selecttree;
    }
    else{
      $contentAssocs['message'] = $contentAssocs['message'] . '<br>posting failed';
    }
  }

  //foren holen
 if (DEBUG){
    $forums = $myDBAccess->getAllForums($cid);
  }
  else{
    $forums = $myDBAccess->getForumsOfPerson($uid, $cid);
  }

  if (empty($forums)){
    $forums = array();
  }

  //selektionen updaten
  if (!empty($HTTP_GET_VARS['select'])){
    $tselect = $HTTP_GET_VARS['select'];
    $temp = session('forum_msgselect', false);
    if (!emptystring($temp)){
      $temp[$tselect] = true;
    }
    else{
      $temp = array();
      $temp[$tselect] = true;
    }
    $_SESSION['forum_msgselect'] = $temp;
  }
  if (!empty($HTTP_GET_VARS['unselect'])){
    $tunselect = $HTTP_GET_VARS['unselect'];
    if (!emptystring(session('forum_msgselect', false))){
      $temp = session('forum_msgselect', false);
      $temp[$tunselect] = false;
      $_SESSION['forum_msgselect'] = $temp;
    }
  }
  if (!empty($HTTP_GET_VARS['forumsel'])){
    $tselect = $HTTP_GET_VARS['forumsel'];
    $temp = session('forum_forumselect', false);
    if (!emptystring($temp)){
      $temp[$tselect] = true;
    }
    else{
      $temp = array();
      $temp[$tselect] = true;
    }
    $_SESSION['forum_forumselect'] = $temp;
  }
  if (!empty($HTTP_GET_VARS['forumunsel'])){
    $tunselect = $HTTP_GET_VARS['forumunsel'];
    if (!emptystring(session('forum_forumselect', false))){
      $temp = session('forum_forumselect', false);
      $temp[$tunselect] = false;
      $_SESSION['forum_forumselect'] = $temp;
    }
  }

  $ffs = session('forum_forumselect', false);
  if (emptystring($ffs)){
    $ffs = array();
  }

  $fms = session('forum_msgselect', false);
  if (emptystring($fms)){
    $fms = array();
  }

  buildForumtemplates($forums, $ffs, $fms, session('select', false), $contentAssocs);

  $content->assign($contentAssocs);
  $content->parse();

  include('./include/usermenu.inc.php');

  $main = new Template(TPLPATH . 'frame.tpl');
  $mainassocs = defaultAssocArray();
  $mainassocs['title'] = 'Forums of ' . encodeText(session('uname', false));
  $mainassocs['content'] = $content->getOutput();
  $mainassocs['menu'] = &$menu;
  $mainassocs['navigator'] = encodeText(session('uname', false)) . '  |  Forums';

  $main->assign($mainassocs);
  $main->parse();
  $main->output();
}

?>
