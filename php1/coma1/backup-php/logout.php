<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1',true);

require_once('./include/header.inc.php');

  if (isset($_SESSION['password'])) {
    unset($_SESSION['password']);
  }
  if (isset($_SESSION['uname'])) {
    unset($_SESSION['uname']);
  }
  if (isset($_SESSION['uid'])) {
    unset($_SESSION['uid']);
  }
  if (isset($_SESSION['confid'])) {
    unset($_SESSION['confid']);
  }
  $_SESSION['message'] = 'Erfolgreich abgemeldet!';

redirect('index.php');

?>
