<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

define('IN_COMA1',true);
define('NEED_NO_LOGIN', true);
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
  $_SESSION['message'] = 'Sie haben sich erfolgreich abgemeldet!';

redirect('index.php');

?>
