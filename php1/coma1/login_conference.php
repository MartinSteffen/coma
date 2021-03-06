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

if (isset($_POST['confid'])) {
  /* Einloggen zur Konferenz confid */
  $_SESSION['confid'] = $_POST['confid'];
  checkAccess(0);
  redirect('index.php');
}
else {
  $_SESSION['message'] = 'Login to conference failed! Please try again!';
  redirect('main_conferences.php');
}

?>