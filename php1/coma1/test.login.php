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

function checkError($class) {
  $s = $class->getLastError();
  if (!empty($s)) {
    echo $s;
  }
}

require_once('./include/class.mysql.inc.php');
require_once('./include/class.session.inc.php');
require_once('./include/class.template.inc.php');
require_once('./include/class.dbaccess.inc.php');

$mySql = new MySql();
checkError($mySql);

$mySession = new Session($mySql);
checkError($mySession);

if (! isset($_SESSION['count'])) {
   $_SESSION['count'] = 1;
} else {
   $_SESSION['count']++;
}

checkError($mySession);

echo $_SESSION['count'];

?>
