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
/**
 * User muss auf der Seite nicht eingeloggt sein
 *
 * @ignore
 */
define('NEED_NO_LOGIN', true);
require_once('./include/header.inc.php');

if (checklogin()) {
  $message = 'You have been successfully signed out.';
}
else {
  $message = 'There was nothing to sign out.';
}

// Clear all Session Informations besides message
$_SESSION = array( 'message' => $message );

redirect('login.php');

?>
