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
require_once('./include/class.person.inc.php');

$mySql = new MySql();
$dbAccess = new DBAccess($mySql);
$s = $dbAccess->getLastError();

if (!empty($s)) {
  echo($s);
  die();
}
echo('<b>Else</b>: "Alles roger in Kambodscher."<br><br>');

$id = $dbAccess->getPersonIdByEmail('hase@braten.org');
echo('ID = '.$id.'<br>');
$p = $dbAccess->getPerson($id);
echo($p->strFirstName.' '.$p->strLastName.' '.$p->intRole);


?>