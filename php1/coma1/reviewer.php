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

// Haupt-Ansicht fuer den Reviewer
$mainPage = new Template(TPLPATH.'main.tpl');
$menue = new Template(TPLPATH.'nav_reviewer.tpl');


$strMainAssocs = defaultAssocArray();
$strMainAssocs['titel'] = 'Chair-Verwaltung';
$strMainAssocs['content'] = 'Eingeloggt! als: ' . $_SESSION['uname'];
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] = '';
$strMainAssocs['body'] = '';
$strMenueAssocs['loginName'] = $_SESSION['uname'];
$mainPage->assign($strMainAssocs);

$menue->assign(defaultAssocArray());

$mainPage->parse();
$mainPage->output();

?>
