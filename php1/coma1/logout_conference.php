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

  session_delete('confid');

  $_SESSION['message'] = 'You have been successfully logged out of the conference.';

redirect('main_conferences.php');

?>
