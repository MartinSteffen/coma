<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core 
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
require_once('./include/header.inc.php');

$content = new Template(TPLPATH.'reviewer_prefers.tpl');
$strContentAssocs = defaultAssocArray();
$content->assign($strContentAssocs);

$actMenu = REVIEWER;
$actMenuItem = 3;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Edit preferences of reviewer '.session('uname');
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = session('uname').'  |  Reviewer  |  Preferences';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>