<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

define('IN_COMA1', true);
require_once('./include/header.inc.php');

if (isset($_POST['confid'])) {
  /* Einloggen zur Konferenz confid */
    redirect('conf_profile.php');
  }
  else {
    $_SESSION['message'] = 'Login to conference failed! Please try again!';
    redirect('main_conferences.php');
  }
}

?>