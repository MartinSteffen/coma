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

// Lade die Daten des Benutzers
if (isset($_GET['userid'])) {
  $objPerson = $myDBAccess->getPersonDetailed($_GET['userid']);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving person.', $myDBAccess->getLastError());
  }
  else if (empty($objPerson)) {
    error('Person does not exist in database.', '');
  }
}
else {
  redirect('user_users.php');
}

$content = new Template(TPLPATH.'view_profile.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['first_name']  = encodeText($objPerson->strFirstName);
$strContentAssocs['last_name']   = encodeText($objPerson->strLastName);
$strContentAssocs['email']       = encodeText($objPerson->strEmail);
$strContentAssocs['email_link']  = 'mailto:'.$objPerson->strEmail;
$strContentAssocs['name_title']  = encodeText($objPerson->strTitle);
$strContentAssocs['affiliation'] = encodeText($objPerson->strAffiliation);
$strContentAssocs['address']     = encodeText($objPerson->getAddress());
$strContentAssocs['country']     = encodeText($objPerson->getCountry());
$strContentAssocs['phone']       = encodeText($objPerson->strPhone);
$strContentAssocs['fax']         = encodeText($objPerson->strFax);
$content->assign($strContentAssocs);

include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'User profile';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
global $strRoles;
if (isset($_SESSION['menu']) && !empty($_SESSION['menu'])) {
  $strMenu = $strRoles[(int)$_SESSION['menu']];
}
else {
  $strMenu = 'Conference';
}
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  '.$strMenu.'  |  User profile';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>