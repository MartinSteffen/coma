<?php
/**
 * @version $Id: chair.php 618 2004-12-10 12:18:41Z waller $
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

// Haupt-Ansicht fuer den Autor
$mainPage = new Template(TPLPATH.'main.tpl');
$menue = new Template(TPLPATH.'nav_autor.tpl');


$strMainAssocs = defaultAssocArray();
$strMainAssocs['titel'] = 'Autor-Verwaltung';
$strMainAssocs['content'] = 'Eingeloggt! als: ' . $_SESSION['uname'];
$strMainAssocs['menue'] =& $menue;
$strMainAssocs['submenue'] = '';
$strMainAssocs['body'] = '';
$mainPage->assign($strMainAssocs);

$strMenueAssocs = defaultAssocArray();
$strMenueAssocs['loginName'] = $_SESSION['uname'];
$menue->assign($strMenueAssocs);

$mainPage->parse();
$mainPage->output();

?>
