<?php
/**
 * @version $Id: login.php 2077 2005-01-17 22:53:45Z waller $
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

define('NEED_NO_LOGIN', false);
require_once('../php1/coma1/include/header.inc.php');

$i = 100;
$j = 101;

while ($i < $j) {
	addConference('Conference'.$i,'Homepage'.$i,'Description'.$i,'AbstractDeadline',
                         'PaperDeadline', 'ReviewDeadline', 'FinalDeadline',
                         'Notification', 'ConferenceStart', 'ConferenceEnd');
    $i++;
    }
