<?php
/**
 * @version $Id: start.php 803 2004-12-13 22:55:56Z miesling $
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
require_once('./include/header.inc.php');

$main = new Template(TPLPATH.'frame.tpl');

$content = new Template(TPLPATH.'login.tpl');
$content->assign(defaultAssocArray());

$strMainAssocs = defaultAssocArray();
$strMainAssocs['body'] = &$content;
$strMainAssocs['title'] = 'Login';

require_once(TPLPATH.'startmenu.php');
$strMainAssocs['menu'] = openStartMenuItem(1);

$strPath = array('CoMa'=>'', 'Login'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>