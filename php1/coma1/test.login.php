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
require_once('./include/class.session.inc.php');
require_once('./include/class.template.inc.php');
require_once('./include/class.dbaccess.inc.php');

$mySql = new MySql();
$mySession = new Session($mySql);

echo 'OK';

?>