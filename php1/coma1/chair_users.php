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

global $intRoles;
global $strRoles;

if (isset($_POST['action'])) {
  if ($_POST['action'] == 'delete') {
    // Benutzer loeschen (?)
  }
  else if ($_POST['action'] == 'editrole') {
    $intPersonId = $_POST['userid'];
    $intRoleType = $_POST['roletype'];
    // Benutzerrolle bearbeiten
    if ($_POST['submit'] == 'add') {
      $myDBAccess->addRole($intPersonId, $intRoleType, session('confid'), true);
      if ($myDBAccess->failed()) {
        error('Error updating role table.', $myDBAccess->getLastError());
      }
    }
    else if ($_POST['submit'] == 'accept') {
      $myDBAccess->acceptRole($intPersonId, $intRoleType, session('confid'));
      if ($myDBAccess->failed()) {
        error('Error updating role table.', $myDBAccess->getLastError());
      }
    }
    else if ($_POST['submit'] == 'remove' || $_POST['submit'] == 'reject') {
      $myDBAccess->deleteRole($intPersonId, $intRoleType, session('confid'));
      if ($myDBAccess->failed()) {
        error('Error updating role table.', $myDBAccess->getLastError());
      }
    }
  }
}
if (isset($_GET['order']) || isset($_POST['order'])) {
  $intOrder = (isset($_GET['order']) ? $_GET['order'] : $_POST['order']);
}
else {
  $intOrder = 0;
}

$objPersons = $myDBAccess->getUsersOfConference(session('confid'), $intOrder);
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
    $strItemAssocs = defaultAssocArray();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['user_id'] = encodeText($objPerson->intId);
    $strItemAssocs['name'] = encodeText($objPerson->getName(1));
    $strItemAssocs['email'] = encodeText($objPerson->strEmail);
    $strItemAssocs['email_link'] = encodeText('mailto:'.$objPerson->strEmail);
    $strItemAssocs['targetform'] = 'chair_users.php';
    $strItemAssocs['order'] = $intOrder;
    $strItemAssocs['roles'] = '';
    for ($i = 0; $i < count($intRoles); $i++) {
      $roles = new Template(TPLPATH.'edit_roles.tpl');
      $strRolesAssocs = defaultAssocArray();
      $strRolesAssocs['targetform'] = 'chair_users.php';
      $strRolesAssocs['user_id'] = encodeText($objPerson->intId);
      $strRolesAssocs['role_type'] = encodeText($intRoles[$i]);
      $strRolesAssocs['role_name'] = encodeText($strRoles[$intRoles[$i]]);
      $strRolesAssocs['line_no'] = $lineNo;
      $strRolesAssocs['order'] = $intOrder;
      if ($objPerson->hasRole($intRoles[$i])) {
      	if ($objPerson->intId != session('uid') || $intRoles[$i] != CHAIR) {
      	  $strRolesAssocs['if'] = array(1);
      	}
      	else {
      	  $strRolesAssocs['if'] = array(4);
      	}
      }
      else if ($objPerson->hasRequestedRole($intRoles[$i])) {
        $strRolesAssocs['if'] = array(3);
      }
      else {
        $strRolesAssocs['if'] = array(2);
      }      
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