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




$headerPage = new Template(TPLPATH.'header.tpl');


$strMainAssocs = defaultAssocArray();
$strMainAssocs['content'] = 'Eingeloggt!';


$header->assign($strMainAssocs);

$header->parse();
$header->output();

?>
