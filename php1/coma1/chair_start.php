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
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), CHAIR);
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');
}

$ifArray = array();
$content = new Template(TPLPATH.'chair_start.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['request_no'] = '';
$strContentAssocs['papers_no'] = '';
$strContentAssocs['acc_papers_no'] = '';
$strContentAssocs['acc_date'] = '';
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
  $ifArray[] = 1;
}
$intUndistributedPapers = $myDBAccess->getNumberOfPapers(session('confid')) -
                          $myDBAccess->getNumberOfDistributedPapers(session('confid'));
if ($myDBAccess->failed()) {
  error('get num of undistributed papers',$myDBAccess->getLastError());
}
if ($intUndistributedPapers > 0) {
  $strContentAssocs['papers_no'] = encodeText($intUndistributedPapers);
  $ifArray[] = 3;
}
$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get conference details',$myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('conference '.session('confid').' does not exist in database.','');
}
if (strtotime($objConference->strReviewDeadline) <= strtotime("now")) {
  $strContentAssocs['acc_papers_no'] = encodeText($intUndistributedPapers);
  $strContentAssocs['acc_date'] = encodeText(emptytime(strtotime($objConference->strNotification)));
  $ifArray[] = 4;  
}
$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$_SESSION['menu'] = CHAIR;
$_SESSION['menuitem'] = 1;
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