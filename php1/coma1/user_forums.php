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

// Pruefe Zugriffsberechtigung auf die Seite
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');	
}

$content = new Template(TPLPATH.'user_forumlist.tpl');
$strContentAssocs = defaultAssocArray();
$content->assign($strContentAssocs);

$_SESSION['menu'] = 0;
$_SESSION['menuitem'] = 3;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$objForums = $myDBAccess->getAllForums(session('confid'));
if ($myDBAccess->failed()) {
  error('An error occured during retrieving forums!',
        $myDBAccess->getLastError());
}
$strMainAssocs['title'] = 'Conference forums for '.encodeText(session('uname'));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Forums';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>