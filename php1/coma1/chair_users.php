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
checkAccess(CHAIR);

$content = new Template(TPLPATH.'chair_userlist.tpl');
$strContentAssocs = defaultAssocArray();

if (isset($_POST['action'])) {
  if ($_POST['action'] == 'delete') {
    // Benutzer loeschen (?)
  }
  else if ($_POST['action'] == 'editrole') {
    $intPersonId = $_POST['userid'];
    $intRoleType = $_POST['roletype'];

    $objChair = $myDBAccess->getPerson(session('uid'));
    if ($myDBAccess->failed()) {
      error('get chair data', $myDBAccess->getLastError());
    }
    $strFrom = '"'.$objChair->getName(2).'" <'.$objChair->strEmail.'>';
    $objPerson = $myDBAccess->getPerson($intPersonId);
    if ($myDBAccess->failed()) {
      error('get person data', $myDBAccess->getLastError());
    }
    $objConference = $myDBAccess->getConferenceDetailed(session('confid'));
    if ($myDBAccess->failed()) {
      error('get conference data', $myDBAccess->getLastError());
    }
    $strMailAssocs = defaultAssocArray();
    $strMailAssocs['chair']      = $objChair->getName(2);
    $strMailAssocs['name']       = $objPerson->getName(2);
    $strMailAssocs['conference'] = $objConference->strName;
    $strMailAssocs['role']       = $strRoles[$intRoleType];

    // Benutzerrolle bearbeiten
    if ($_POST['submit'] == 'add') {
      $myDBAccess->addRole($intPersonId, $intRoleType, session('confid'), true);
      if ($myDBAccess->failed()) {
        error('updating role table', $myDBAccess->getLastError());
      }
      $mail = new Template(TPLPATH.'mail_newrole.tpl');
      $mail->assign($strMailAssocs);
      $mail->parse();
      sendMail($intPersonId, "New role in conference '".$strMailAssocs['conference']."'",
               $mail->getOutput(), $strFrom);
    }
    elseif ($_POST['submit'] == 'accept') {
      $myDBAccess->acceptRole($intPersonId, $intRoleType, session('confid'));
      if ($myDBAccess->failed()) {
        error('updating role table', $myDBAccess->getLastError());
      }
      $mail = new Template(TPLPATH.'mail_acceptrole.tpl');
      $mail->assign($strMailAssocs);
      $mail->parse();
      sendMail($intPersonId, "New role in conference '".$strMailAssocs['conference']."'",
               $mail->getOutput(), $strFrom);
    }
    elseif ($_POST['submit'] == 'remove') {
      // Bereits zugeordnete Reviewer aus Distribution-Tabelle entfernen
      if ($intRoleType == REVIEWER) {
        $myDBAccess->deleteReviewerDistribution($intPersonId, session('confid'));
        if ($myDBAccess->failed()) {
          error('updating distribution table', $myDBAccess->getLastError());
        }
      }
      $myDBAccess->deleteRole($intPersonId, $intRoleType, session('confid'));
      if ($myDBAccess->failed()) {
        error('updating role table', $myDBAccess->getLastError());
      }
      $mail = new Template(TPLPATH.'mail_removerole.tpl');
      $mail->assign($strMailAssocs);
      $mail->parse();
      sendMail($intPersonId, "Role removed in conference '".$strMailAssocs['conference']."'",
               $mail->getOutput(), $strFrom);
    }
    elseif ($_POST['submit'] == 'reject') {
      $myDBAccess->deleteRole($intPersonId, $intRoleType, session('confid'));
      if ($myDBAccess->failed()) {
        error('updating role table', $myDBAccess->getLastError());
      }
      $mail = new Template(TPLPATH.'mail_rejectrole.tpl');
      $mail->assign($strMailAssocs);
      $mail->parse();
      sendMail($intPersonId, "Role rejected in conference '".$strMailAssocs['conference']."'",
               $mail->getOutput(), $strFrom);
    }
  }
}

if (isset($_GET['order'])) {
  if ((int)session('orderusers', false) != $_GET['order']) {
    $_SESSION['orderusers'] = $_GET['order'];
  }
  else {
    unset($_SESSION['orderusers']);
  }
}
$intOrder = (int)session('orderusers', false);
$ifArray = array($intOrder);

$objPersons = $myDBAccess->getUsersOfConference(session('confid'), $intOrder);
if ($myDBAccess->failed()) {
  error('gather list of users', $myDBAccess->getLastError());
}

$strContentAssocs['targetpage'] = 'chair_users.php';
$strContentAssocs['message']    = session('message', false);
session_delete('message');
$strContentAssocs['if'] = $ifArray;
$strContentAssocs['lines'] = '';
if (!empty($objPersons)) {
  $lineNo = 1;
  foreach ($objPersons as $objPerson) {
    $strItemAssocs = defaultAssocArray();
    $strItemAssocs['line_no']    = $lineNo;
    $strItemAssocs['user_id']    = encodeText($objPerson->intId);
    $strItemAssocs['name']       = encodeText($objPerson->getName(1));
    $strItemAssocs['email']      = encodeText($objPerson->strEmail);
    $strItemAssocs['email_link'] = encodeText('mailto:'.$objPerson->strEmail);
    $strItemAssocs['targetform'] = 'chair_users.php';
    $ifArray = array();
    if ($objPerson->hasRole(REVIEWER)) {
      $ifArray[] = 1;
    }
    if ($objPerson->hasAnyRoleRequest()) {      
      $strItemAssocs['line_no']  = 'alert-'.$lineNo;
      $ifArray[] = 'ALERT';
    }
    $strItemAssocs['if'] = $ifArray;
    $strItemAssocs['roles'] = '';
    for ($i = 0; $i < count($intRoles); $i++) {
      $roles = new Template(TPLPATH.'edit_roles.tpl');
      $strRolesAssocs = defaultAssocArray();
      $strRolesAssocs['targetform'] = 'chair_users.php';
      $strRolesAssocs['user_id']    = encodeText($objPerson->intId);
      $strRolesAssocs['role_type']  = encodeText($intRoles[$i]);
      $strRolesAssocs['role_name']  = encodeText($strRoles[$intRoles[$i]]);
      $strRolesAssocs['line_no']    = $strItemAssocs['line_no'];
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
  $strItemAssocs['colspan'] = '5';
  $strItemAssocs['text'] = 'There are no users available.';
  $emptyList = new Template(TPLPATH.'empty_list.tpl');
  $emptyList->assign($strItemAssocs);
  $emptyList->parse();
  $strContentAssocs['lines'] = $emptyList->getOutput();
}

$content->assign($strContentAssocs);

$_SESSION['menu'] = CHAIR;
$_SESSION['menuitem'] = 2;
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