<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
require_once('./include/header.inc.php');

if (isset($_GET['paperid'])) {
  // Filename und Mime bekommen
  $myDBAccess->getPaperSimple($_GET['paperid']);
  
}
else {
  // Keine PaperID
  error('Get Paper', 'No Paper to get...');
}


?>