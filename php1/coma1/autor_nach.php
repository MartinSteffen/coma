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

// Haupt-Ansicht fuer den Chair
$mainPage = new Template(TPLPATH.'main.tpl');
$menue = new Template(TPLPATH.'nav_autor.tpl');
$submenue = new Template(TPLPATH.'nav_nach.tpl');

$strMainAssocs = defaultAssocArray();
$strMainAssocs['titel'] = 'Autor-Verwaltung';
$strMainAssocs['content'] = 'Eingeloggt! als: ' . $_SESSION['uname'];
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] =& $submenue;
$strMainAssocs['body'] = '';


$submenue->assign(defaultAssocArray());
$mainPage->assign($strMainAssocs);

$strMenueAssocs = defaultAssocArray();
$strMenueAssocs['loginName'] = $_SESSION['uname'];
$menue->assign(defaultAssocArray());

$mainPage->parse();
$mainPage->output();

?>