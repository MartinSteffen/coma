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

if (!isset($_POST['confid'])) {
  redirect('main_conferences.php');
}
else if (!isset($_POST['roleid']) ||
         ($_POST['roleid'] != CHAIR && $_POST['roleid'] != AUTHOR &&
          $_POST['roleid'] != REVIEWER && $_POST['roleid'] != PARTICIPANT)) {
  error('Received invalid or empty role as parameter.', '');
}

// Lade die Daten der Konferenz
$objConference = $myDBAccess->getConferenceDetailed($_POST['confid']);
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference config.', $myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('Conference does not exist in database.', '');
}
// Rollenbewerbung zurueckziehen
else if (isset($_POST['retreat'])) {
  $myDBAccess->deleteRole(session('uid'), $_POST['roleid'], $_POST['confid']);
  if ($myDBAccess->failed()) {
    error('Error updating role table.', $myDBAccess->getLastError());
  }	
}
// Rollen eintragen bzw. Rollenbewerbung eintragen
else {  
  if ($_POST['roleid'] == PARTICIPANT) {
    $blnAccepted = true;	
  }
  else {
    $blnAccepted = $objConference->blnAutoActivateAccount;
  }
  $myDBAccess->addRole(session('uid'), $_POST['roleid'], $_POST['confid'], $blnAccepted);
  if ($myDBAccess->failed()) {
    error('Error updating role table.', $myDBAccess->getLastError());
  }
}

global $strRoles;

$content = new Template(TPLPATH.'confirm_apply.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['title'] = $objConference->strName;
$strContentAssocs['role'] = $strRoles[$_POST['roleid']];
if (empty($blnAccepted)) {
  $strContentAssocs['if'] = array(1);
}
else {
  $strContentAssocs['if'] = array();
}
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'mainmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(2);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Conferences Overview';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Conferences';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>