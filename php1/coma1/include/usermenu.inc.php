$objPerson = $myDBAccess->getPerson(session('uid'));

$menu = new Template(TPLPATH.'usermenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs[menu1] = '';
$strMenuAssocs[menu2] = '';
$strMenuAssocs[menu3] = '';
$strMenuAssocs[menu4] = '';
$strMenuAssocs['if'] = array();

if ($objPerson->hasRole(CHAIR)) {
  $submenu = new Template(TPLPATH.'chairmenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs[menu1] = $submenu->getOutput();
}

if ($objPerson->hasRole(REVIEWER)) {
  $submenu = new Template(TPLPATH.'reviewermenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs[menu1] = $submenu->getOutput();
}

if ($objPerson->hasRole(AUTHOR)) {
  $submenu = new Template(TPLPATH.'authormenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs[menu1] = $submenu->getOutput();
}

if ($objPerson->hasRole(PARTICIPANT)) {
  $submenu = new Template(TPLPATH.'participantmenu.tpl');
  $strSubmenuAssocs = defaultAssocArray();
  $strSubmenuAssocs['if'] = array();
  $submenu->assign($strSubmenuAssocs);
  $submenu->parse();
  $strMenuAssocs[menu1] = $submenu->getOutput();
}

$menu->assign($strMenuAssocs);