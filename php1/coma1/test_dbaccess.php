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
define('IN_COMA1',true);

require_once('./include/class.mysql.inc.php');
require_once('./include/class.dbaccess.inc.php');
/*require_once('./include/class.person.inc.php');
require_once('./include/class.persondetailed.inc.php');
require_once('./include/class.paper.inc.php');
require_once('./include/class.papersimple.inc.php');
require_once('./include/class.paperdetailed.inc.php');*/


$mySql = new MySql();
$dbAccess = new DBAccess($mySql);
$s = $dbAccess->getLastError();

if (!empty($s)) {
  // einfacher: exit($S); // Jan
  echo($s);
  die();
}
echo('<b>Else</b>: "Alles roger in Kambodscher."<br><br>');

$id = $dbAccess->getPersonIdByEmail('hase@braten.org');
echo('ID = '.$id.'<br>');
$p = $dbAccess->getPersonDetailed($id);
echo($p->strFirstName.' '.$p->strLastName.' '.$p->intRoles.'<br>');
echo($p->strFirstName.' ist '.($p->hasRole(1)?'':'k').'ein Chair.<br>');
echo($p->strFirstName.' ist '.($p->hasRole(2)?'':'k').'ein Reviewer.<br>');
echo($p->strFirstName.' ist '.($p->hasRole(5)?'':'k').'ein Teilnehmer.<br>');

$p = $dbAccess->getPapersOfAuthor(1);
if($p != false) {
  for($i = 0; $i < count($p); $i++) {
    echo('Titel: '.$p->strTitle.', Autor: '.$p->strAuthor.'<br>');
  }
}

?>