<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

define('IN_COMA1', true);
define('NEED_NO_LOGIN', true);
require_once('./include/header.inc.php');

$main = new Template(TPLPATH.'frame.tpl');

$content = new Template(TPLPATH.'impressum.tpl');
$content->assign(defaultAssocArray());

$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Impressum';
$strMainAssocs['content'] = &$content;

require_once(TPLPATH.'startmenu.php');
$strMainAssocs['menu'] = openStartMenuItem(4);

$strPath = array('CoMa'=>'', 'Impressum'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>