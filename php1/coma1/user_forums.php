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

$content = new Template(TPLPATH.'user_forumlist.tpl');
$strContentAssocs = defaultAssocArray();
$content->assign($strContentAssocs);

$actMenu = 0;
$actMenuItem = 3;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$objForums = $myDBAccess->getAllForums(session('confid'));
if ($myDBAccess->failed()) {
  error('An error occured during retrieving forums!',
        $myDBAccess->getLastError());
}
$strMainAssocs['title'] = 'Conference forums for '.session('uname');
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = session('uname').'  |  Forums';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>