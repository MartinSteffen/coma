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
  error('get user list',$myDBAccess->getLastError());
}

//global $intRoles;
//global $strRoles;

$content = new Template(TPLPATH.'user_userlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['targetpage'] = 'user_users.php';
$strContentAssocs['message'] = session('message', false);
session_delete('message');
$strContentAssocs['if'] = $ifArray;
$strContentAssocs['lines'] = '';
if (!empty($objPersons)) {
  $lineNo = 1;
  foreach ($objPersons as $objPerson) {
    $strItemAssocs = defaultAssocsArray();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['user_id'] = $objPerson->intId;    
    $strItemAssocs['name'] = encodeText($objPerson->getName(1));
    $strItemAssocs['email'] = encodeText($objPerson->strEmail);
    $strItemAssocs['email_link'] = 'mailto:'.$objPerson->strEmail;
    $strItemAssocs['roles'] = '';
    for ($i = 0; $i < count($intRoles); $i++) {
      if ($objPerson->hasRole($intRoles[$i])) {
        if (!empty($strItemAssocs['roles'])) {
      	  $strItemAssocs['roles'] .= ', ';
        }
        $strItemAssocs['roles'] .= $strRoles[$intRoles[$i]];
      }
    }
    if ($objPerson->hasRole(REVIEWER)) {
      $strItemAssocs['if'] = array(1);
    }
    else {
      $strItemAssocs['if'] = array();
    }
    $userItem = new Template(TPLPATH.'user_userlistitem.tpl');
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

$_SESSION['menu'] = 0;
$_SESSION['menuitem'] = 7;
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