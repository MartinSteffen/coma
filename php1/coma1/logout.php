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

  session_delete('password');
  session_delete('uname');
  session_delete('uid');
  session_delete('confid');

  $_SESSION['message'] = 'Sie haben sich erfolgreich abgemeldet!';

redirect('login.php');

?>
