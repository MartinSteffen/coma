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

// Einlog-Bildschirm
$mainPage = new Template(TPLPATH.'main.tpl');

$strMainAssocs = defaultAssocArray();
$strMainAssocs['content'] = 'Eingeloggt! als: ' . $_SESSION['uname'];


$mainPage->assign($strMainAssocs);

$mainPage->parse();
$mainPage->output();

?>
