<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

$objPerson = $myDBAccess->getPerson(session('uid'), session('confid'));
if ($myDBAccess->failed()) {
  error('Error processing the user menu.', $myDBAccess->getLastError());
}
else if (empty($objPerson)) {
  error('User does not exist in database.', $myDBAccess->getLastError());
}

$actMenu     = (int)session('menu', false);
$actMenuItem = (int)session('menuitem', false);

$menu = new Template(TPLPATH.'usermenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['menu1'] = '';
$strMenuAssocs['menu2'] = '';
$strMenuAssocs['menu3'] = '';
$strMenuAssocs['menu4'] = '';
$strMenuAssocs['if'] = array();

if ($actMenu == 0) {
  $strMenuAssocs['if'] = array($actMenuItem);
}

if ($objPerson->hasRole(CHAIR, session('confid'))) {
  $submenu = new Template(TPLPATH.'chairmenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  if ($actMenu == CHAIR) {
    $strSubmenuAssocs['if'] = array($actMenuItem);
  }
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs['menu1'] = $submenu->getOutput();
}

if ($objPerson->hasRole(REVIEWER, session('confid'))) {
  $submenu = new Template(TPLPATH.'reviewermenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  if ($actMenu == REVIEWER) {
    $strSubmenuAssocs['if'] = array($actMenuItem);
  }
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs['menu2'] = $submenu->getOutput();
}

if ($objPerson->hasRole(AUTHOR, session('confid'))) {
  $submenu = new Template(TPLPATH.'authormenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  if ($actMenu == AUTHOR) {
    $strSubmenuAssocs['if'] = array($actMenuItem);
  }
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs['menu3'] = $submenu->getOutput();
}

if ($objPerson->hasRole(PARTICIPANT)) {
  $submenu = new Template(TPLPATH.'participantmenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  if ($actMenu == PARTICIPANT) {
    $strSubmenuAssocs['if'] = array($actMenuItem);
  }
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs['menu4'] = $submenu->getOutput();
}

$menu->assign($strMenuAssocs);

?>