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

$mySql = new MySql();
$dbAccess = new DBAccess($mySql);
$s = $dbAccess->getLastError();

if (!empty($s)) {
  echo($s);
  die();
}
echo('Else: "Alles roger in Kambodscher." ');

echo($dbAccess->getPersonIdByEmail('hase@braten.org'));


?>