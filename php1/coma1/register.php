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

$content = new Template(TPLPATH.'register.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = '';
$content->assign($strContentAssocs);

$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Neuen Benutzer registrieren';
$strMainAssocs['content'] = &$content;

require_once(TPLPATH.'startmenu.php');
$strMainAssocs['menu'] = openStartMenuItem(1);

$strPath = array('CoMa'=>'', 'Registrieren'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>