<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 * @todo Inhalte!
 */
/***/

/**
 * Wichtig, damit Coma1 Dateien eingebunden werden koennen
 *
 * @ignore
 */
define('IN_COMA1', true);
/**
 * User muss auf der Seite nicht eingeloggt sein
 *
 * @ignore
 */
define('NEED_NO_LOGIN', true);
require_once('./include/header.inc.php');

$menu = new Template(TPLPATH.'emptymenu.tpl');
$menu->assign(defaultAssocArray());

$content = new Template(TPLPATH.'error.tpl');
$strErrorAssocs = defaultAssocArray();
$strErrorAsscos = session('message', false);
session_delete('message');
$content->assign($strErrorAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Error';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = 'CoMa  |  Error';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>