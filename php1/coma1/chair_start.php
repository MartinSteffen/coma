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

$content = new Template(TPLPATH.'chair_start.tpl');
$strContentAssocs = defaultAssocArray();

$objPersons = $myDBAccess->getUsersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get user list',$myDBAccess->getLastError());
}
$intRoleRequests = 0;
foreach ($objPersons as $objPerson) {
  if ($objPerson->hasAnyRoleRequest()) {
    $intRoleRequests++;
  }
}
if ($intRoleRequests > 0) {
  $strContentAssocs['request_no'] = encodeText($intRoleRequests);
  $strContentAssocs['if'] = array(1);	
}
$content->assign($strContentAssocs);

$actMenu = CHAIR;
$actMenuItem = 1;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Main tasks for chair '.encodeText(session('uname'));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Main';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>