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
$strContentAssocs['message'] = session('message', false);
session_delete('message');
$strContentAssocs['first_name']  = encodeText($objPerson->strFirstName);
$strContentAssocs['last_name']   = encodeText($objPerson->strLastName);
$strContentAssocs['email']       = encodeText($objPerson->strEmail);
$strContentAssocs['email_link']  = 'mailto:'.$objPerson->strEmail;
$strContentAssocs['name_title']  = encodeText($objPerson->strTitle);
$strContentAssocs['affiliation'] = encodeText($objPerson->strAffiliation);
$strContentAssocs['street']      = encodeText($objPerson->strStreet);
$strContentAssocs['city']        = encodeText($objPerson->strCity);
$strContentAssocs['postalcode']  = encodeText($objPerson->strPostalCode);
$strContentAssocs['state']       = encodeText($objPerson->strState);
$strContentAssocs['country']     = encodeText($objPerson->strCountry);
$strContentAssocs['phone']       = encodeText($objPerson->strPhone);
$strContentAssocs['fax']         = encodeText($objPerson->strFax);
$content->assign($strContentAssocs);

$actMenu = 0;
$actMenuItem = 6;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'User profile';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Users';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>