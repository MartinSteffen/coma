<?php
/**
 * @version $Id: chair.php 547 2004-12-08 00:33:38Z waller $
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




$headerPage = new Template(TPLPATH.'main.tpl');


$strMainAssocs = defaultAssocArray();
$strMainAssocs['content'] = 'Eingeloggt!';


$headerPage->assign($strMainAssocs);

$headerPage->parse();
$headerPage->output();

?>
