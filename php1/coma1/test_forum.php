<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage Testing
 */
/***/

/**
 * Wichtig damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);

require_once('./include/header.inc.php');

function printMessage($objMessage, $indent = 0) { 
  if ($objMessage) {   
    for ($i = 0; $i < $indent; $i++) {
      echo ('&nbsp;&nbsp;&nbsp;&nbsp;');
    }
    echo('-- '.$objMessage->strSubject.'<br>');    
    for ($n = 0; $n < $objMessage->getNextMessageCount(); $n++) {
      printMessage($objMessage->getNextMessage($n), $indent + 1);
    }  
  }
}

$forum = $myDBAccess->getForumDetailed(1);
if ($forum) {  
  echo('<br><b>'.$forum->getThreadCount().' Thread'.
       ($forum->getThreadCount() <> 1 ? 's' : '').
       ' in Forum \''.$forum->strTitle.'\':</b><br><br>');   
  for ($n = 0; $n < $forum->getThreadCount(); $n++) {
    printMessage($forum->getThread($n), 1);  
  }
}

?>