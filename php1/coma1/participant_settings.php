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
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), PARTICIPANT);
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');	
}

$content = new Template(TPLPATH.'participant_settings.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $strContentAssocs['if'] = array(9);
}
$content->assign($strContentAssocs);

$_SESSION['menu'] = PARTICIPANT;
$_SESSION['menuitem'] = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Edit settings of participant '.encodeText(session('uname'));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Participant  |  Settings';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>