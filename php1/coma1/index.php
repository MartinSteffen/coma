<?php
/**
 * @version $Id: index.php 721 2004-12-12 14:01:30Z waller $
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

// Hauptsächlich Redirect auf entsprechende Seite...
redirect('chair.php');
?>
