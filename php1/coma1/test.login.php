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

require_once('./include/header.inc.php');
$mySession->sessionGC(120);
checkError($mySession);

if (! isset($_SESSION['count'])) {
   $_SESSION['count'] = 1;
} else {
   $_SESSION['count']++;
}

echo SID;
echo $_SESSION['count'];

?>
