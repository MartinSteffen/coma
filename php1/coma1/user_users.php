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

$content = new Template(TPLPATH.'user_userlist.tpl');
$strContentAssocs = defaultAssocArray();
$objPersons = $myDBAccess->getUsersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get user list',$myDBAccess->getLastError());
}

$strContentAssocs['message'] = session('message', false);
session_delete('message');
$strContentAssocs['if'] = array();
$strContentAssocs['lines'] = '';
if (!empty($objPersons)) {
  $lineNo = 1;
  foreach ($objPersons as $objPerson) {
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['user_id'] = $objPerson->intId;    
    $strItemAssocs['name'] = encodeText($objPerson->getName());
    $strItemAssocs['email'] = encodeText($objPerson->strEmail);
    $strItemAssocs['email_link'] = 'mailto:'.$objPerson->strEmail;
    $userItem = new Template(TPLPATH.'user_userlistitem.tpl');
    $userItem->assign($strItemAssocs);
    $userItem->parse();
    $strContentAssocs['lines'] .= $userItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {
  // Benutzerliste ist leer.
}

$content->assign($strContentAssocs);

$actMenu = 0;
$actMenuItem = 6;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'All users for conference';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Conference  |  All users';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>