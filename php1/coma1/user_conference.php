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

$content = new Template(TPLPATH.'user_conference.tpl');
$strContentAssocs = defaultAssocArray();
$content->assign($strContentAssocs);

$actMenu = 0;
$actMenuItem = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed() || empty($objConference) {
  error('An error occured during retrieving actual conference!',
        $myDBAccess->getLastError());
}
$strMainAssocs['title'] = 'Conference '.$objConference->strName;
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = session('uname').'  |  Conference';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>