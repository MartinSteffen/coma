<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage Testing
 */
/***/

/**
 * Wichtig damit Coma1 Datein eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1',true);

require_once('./include/class.dbaccess.inc.php');

$dbAccess = new DBAccess();
$s = $dbAccess->getLastError();

if (!empty($s)) {
  echo($s);
}
else {
  echo('Else: "Alles roger in Kambodscher."');
}

?>