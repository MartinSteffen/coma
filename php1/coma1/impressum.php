<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 * @todo Inhalte
 */
/***/

define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
require_once('./include/header.inc.php');

$menu = new Template(TPLPATH.'startmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(4);
$menu->assign($strMenuAssocs);

$content = new Template(TPLPATH.'impressum.tpl');
$content->assign(defaultAssocArray());

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Impressum';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;

$strPath = array('CoMa'=>'', 'Impressum'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>