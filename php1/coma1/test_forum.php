<?php
/**
 * @version $Id: test_dbaccess.php 412 2004-12-04 20:25:19Z scherzer $
 * @package coma1
 * @subpackage Testing
 */
/***/

/**
 * Wichtig damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1',true);

require_once('./include/class.mysql.inc.php');
require_once('./include/class.dbaccess.inc.php');
/*require_once('./include/class.person.inc.php');
require_once('./include/class.persondetailed.inc.php');
require_once('./include/class.paper.inc.php');
require_once('./include/class.papersimple.inc.php');
require_once('./include/class.paperdetailed.inc.php');*/

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

$mySql = new MySql();
$dbAccess = new DBAccess($mySql);
$s = $dbAccess->getLastError();

if (!empty($s)) {
  // einfacher: exit($S); // Jan
  echo($s);
  die();
}
echo('<b>Else</b>: "Alles roger in Kambodscher."<br><br>');

$forum = $dbAccess->getDetailedForum(1);
if ($forum) {  
  echo('<b>'.count($forum->getThreadCount()).' Thread'.
       (count($forum->getThreadCount()) <> 1 ? 's' : '').
       ' in Forum \''.$forum->title.'\':</b><br><br>');   
  for ($n = 0; $n < $forum->getThreadCount(); $n++) {
    printMessage($forum->getThread($n), 1);  
  }
}

?>