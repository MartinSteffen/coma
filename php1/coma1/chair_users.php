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

$content = new Template(TPLPATH.'chair_userlist.tpl');
$strContentAssocs = defaultAssocArray();
$objPersons = $myDBAccess->getUsersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('get user list',$myDBAccess->getLastError());
}

global $intRoles;
global $strRoles;

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
    $strItemAssocs['target_form'] = 'chair_users.php';
    $strItemAssocs['roles'] = '';
    for ($i = 0; $i < count($intRoles); $i++) {
      $roles = new Template(TPLPATH.'edit_roles.tpl');
      $strRolesAssocs = defaultAssocArray();
      $strRolesAssocs['user_id'] = $objPerson->intId;
      $strRolesAssocs['role_type'] = $intRoles[$i];
      $strRolesAssocs['role_name'] = $strRoles[$intRoles[$i]];      
      $strRolesAssocs['if'] = array(($objPerson->hasRole($intRoles[$i])) ? 1 : 2);      
      $roles->assign($strRolesAssocs);
      $roles->parse();
      $strItemAssocs['roles'] .= $roles->getOutput();
    }    
    $userItem = new Template(TPLPATH.'chair_userlistitem.tpl');
    $userItem->assign($strItemAssocs);
    $userItem->parse();
    $strContentAssocs['lines'] .= $userItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {
  // Benutzerliste ist leer.
  $strItemAssocs = defaultAssocArray();
  $strItemAssocs['colspan'] = '4';
  $strItemAssocs['text'] = 'There are no users available.';
  $emptyList = new Template(TPLPATH.'empty_list.tpl');
  $emptyList->assign($strItemAssocs);
  $emptyList->parse();
  $strContentAssocs['lines'] = $emptyList->getOutput();  
}

$content->assign($strContentAssocs);

$actMenu = CHAIR;
$actMenuItem = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Manage users';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Users';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>