<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 * @todo Sinnvolle Weiterleitungen
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
require_once('./include/header.inc.php');

// Wenn ich hier bin, bin ich eingeloggt!
redirect('main_start.php');

?>