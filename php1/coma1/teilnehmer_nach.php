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


$mainPage = new Template(TPLPATH.'main.tpl');
$menue = new Template(TPLPATH.'nav_teilnehmer.tpl');
$submenue = new Template(TPLPATH.'nav_nach.tpl');

$strMainAssocs = defaultAssocArray();
$strMainAssocs['titel'] = 'Teilnehmer-Verwaltung';
$strMainAssocs['content'] = 'Eingeloggt! als: ' . $_SESSION['uname'];
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] =& $submenue;
$strMainAssocs['body'] = '';
$strMenueAssocs['loginName'] = $_SESSION['uname'];

$menue->assign(defaultAssocArray());
$submenue->assign(defaultAssocArray());
$mainPage->assign($strMainAssocs);

$mainPage->parse();
$mainPage->output();

?>