<?php
/**
 * @version $Id$
 * @package coma1
 */
/***/
if (!defined('IN_COMA1')) {
  exit('Hacking attempt');
}

$objPerson = $myDBAccess->getPerson(session('uid', session('confid')));
if ($mySql->failed()) {
  $_SESSION['message'] = 'An error occured during processing the user menu!<br>'.
                         $mySql->getLastError();
  redirect('error.php');
}

$menu = new Template(TPLPATH.'usermenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['menu1'] = '';
$strMenuAssocs['menu2'] = '';
$strMenuAssocs['menu3'] = '';
$strMenuAssocs['menu4'] = '';
$strMenuAssocs['if'] = array();

if ($objPerson->hasRole(CHAIR, session('confid'))) {
  $submenu = new Template(TPLPATH.'chairmenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs['menu1'] = $submenu->getOutput();
}

if ($objPerson->hasRole(REVIEWER, session('confid'))) {
  $submenu = new Template(TPLPATH.'reviewermenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs['menu2'] = $submenu->getOutput();
}

if ($objPerson->hasRole(AUTHOR, session('confid'))) {
  $submenu = new Template(TPLPATH.'authormenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs['menu3'] = $submenu->getOutput();
}

if ($objPerson->hasRole(PARTICIPANT)) {
  $submenu = new Template(TPLPATH.'participantmenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs['menu4'] = $submenu->getOutput();
}

$menu->assign($strMenuAssocs);

?>