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

$content = new Template(TPLPATH.'participant_start.tpl');
$strContentAssocs = defaultAssocArray();
$content->assign($strContentAssocs);

$actMenu = PARTICIPANT;
$actMenuItem = 1;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Main tasks for participant '.session('uname');
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = session('uname').'  |  Participant  |  Main';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>