<?php
/**
 * @version $Id: chair.php 597 2004-12-09 15:03:14Z waller $
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

// Haupt-Ansicht fuer den Chair
$mainPage = new Template(TPLPATH.'main.tpl');
$menue = new Template(TPLPATH.'nav_chair.tpl');
$submenue = new Template(TPLPATH.'nav_chair_konf.tpl');

$strMainAssocs = defaultAssocArray();
$strMainAssocs['content'] = 'Eingeloggt! als: ' . $_SESSION['uname'];
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] =& $submenue;

$strMenueAssocs = defaultAssocArray();
$strSubMenueAssocs = defaultAssocArray();

$menue->assign($strMenueAssoc);
$submenue->assign($strSubMenueAssoc);
$mainPage->assign($strMainAssocs);

$mainPage->parse();
$mainPage->output();

?>