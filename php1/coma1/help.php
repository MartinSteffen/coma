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

$content = new Template(TPLPATH.'helpmain.tpl');
$content->assign(defaultAssocArray());

$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Hilfe zu CoMa';
$strMainAssocs['content'] = &$content;

require_once(TPLPATH.'startmenu.php');
$strMainAssocs['menu'] = openStartMenuItem(4);

$strPath = array('CoMa'=>'', 'Hilfe'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>