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
else if (!isset($_POST['roletype']) ||
         ($_POST['roletype'] != CHAIR && $_POST['roletype'] != AUTHOR &&
          $_POST['roletype'] != REVIEWER && $_POST['roletype'] != PARTICIPANT)) {
  error('apply as role','Received invalid or empty role as parameter.');
}

// Lade die Daten der Konferenz
$intRoleType = $_POST['roletype'];
$objConference = $myDBAccess->getConferenceDetailed($_POST['confid']);
if ($myDBAccess->failed()) {
  error('Error occured retrieving conference config.', $myDBAccess->getLastError());
}
elseif (empty($objConference)) {
  error('Conference does not exist in database.', '');
}
// Rollenbewerbung zurueckziehen
elseif (isset($_POST['retreat'])) {
  $myDBAccess->deleteRole(session('uid'), $intRoleType, $_POST['confid']);
  if ($myDBAccess->failed()) {
    error('Error updating role table.', $myDBAccess->getLastError());
  }
  redirect('main_conferences.php');
}
// Rollen eintragen bzw. Rollenbewerbung eintragen
else {
  // Teilnehmer muessen nie bestaetigt werden
  if ($_POST['roletype'] == PARTICIPANT) {
    $blnAccepted = true;
  }
  // Chairs und Reviewer muessen immer bestaetigt werden
  elseif ($_POST['roletype'] == CHAIR || $_POST['roletype'] == REVIEWER) {
    $blnAccepted = false;
  }
  else {
    $blnAccepted = $objConference->blnAutoActivateAccount;
  }
  if ($myDBAccess->hasRoleInConference(session('uid'), $_POST['confid'], CHAIR)) {
    $blnAccepted = true; // Chair darf immer
  }  
  elseif ($myDBAccess->failed()) {
    error('Error occured retrieving conference data.', $myDBAccess->getLastError());
  }
  $myDBAccess->addRole(session('uid'), $intRoleType, $_POST['confid'], $blnAccepted);
  if ($myDBAccess->failed()) {
    error('Error updating role table.', $myDBAccess->getLastError());
  }
}

$content = new Template(TPLPATH.'confirm_apply.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['title'] = encodeText($objConference->strName);
$strContentAssocs['role'] = encodeText($strRoles[$intRoleType]);
if (empty($blnAccepted)) {
  $strContentAssocs['if'] = array(2);
}
else {
  $strContentAssocs['if'] = array(1);
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