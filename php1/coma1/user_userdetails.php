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
checkAccess(0);

$popup = (isset($_GET['popup'])) ? true : false;

// Lade die Daten des Benutzers
if (isset($_GET['userid']) || isset($_POST['userid'])) {
  $intPersonId = (isset($_GET['userid']) ? $_GET['userid'] : $_POST['userid']);  
  $objPerson = $myDBAccess->getPersonDetailed($intPersonId, session('confid'));
  if ($myDBAccess->failed()) {
    error('get user details', $myDBAccess->getLastError());
  }
  else if (empty($objPerson)) {
    error('get user details', 'Person '.$intPersonId.' does not exist in database.');
  }
  if (!($objPerson->hasAnyRole()) && !($objPerson->hasAnyRoleRequest())) {
    error('check page access', 'You have no permission to view this page.');
  }
}
else {
  error('get user details', 'No User selected!');
}

$content = new Template(TPLPATH.'view_profile.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['userid']      = encodeText($objPerson->intId);
$strContentAssocs['first_name']  = encodeText($objPerson->strFirstName);
$strContentAssocs['last_name']   = encodeText($objPerson->strLastName);
$strContentAssocs['email']       = encodeText($objPerson->strEmail);
$strContentAssocs['email_link']  = 'mailto:'.encodeText($objPerson->strEmail);
$strContentAssocs['name_title']  = encodeText($objPerson->strTitle);
$strContentAssocs['affiliation'] = encodeText($objPerson->strAffiliation);
$strContentAssocs['address']     = encodeText($objPerson->getAddress());
$strContentAssocs['country']     = encodeText($objPerson->getCountry());
$strContentAssocs['phone']       = encodeText($objPerson->strPhone);
$strContentAssocs['fax']         = encodeText($objPerson->strFax);
$strContentAssocs['navlink']     = ($popup) ? array( 'CLOSE' ) : array( 'BACK' );
$strContentAssocs['author_papers'] = '';
$strContentAssocs['&popup']      = ($popup ? '&amp;popup' : '');

$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'User profile';

// Pruefe Zugriffsberechtigung auf die Artikelliste
$checkChairRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), CHAIR);
if ($myDBAccess->failed()) {
  error('Error occured during performing permission check.', $myDBAccess->getLastError());
}
if ($checkChairRole && $objPerson->hasRole(AUTHOR)) {
  $objPapers = $myDBAccess->getPapersOfAuthor($objPerson->intId, session('confid'));
  if ($myDBAccess->failed()) {
    error('get paper list of author', $myDBAccess->getLastError());
  }
  $paperList = new Template(TPLPATH.'profile_authorpaperlist.tpl');
  $strPapersAssocs = defaultAssocArray();
  $strPapersAssocs['message']     = '';
  $strPapersAssocs['user_id']     = encodeText($objPerson->intId);
  $strPapersAssocs['&popup']      = ($popup ? '&amp;popup' : '');
  $strPapersAssocs['author_name'] = encodeText($objPerson->getName(0));
  $strPapersAssocs['targetpage']  = 'user_userdetails.php';
  $strPapersAssocs['lines']       = '';
  if (!empty($objPapers)) {
    $lineNo = 1;
    foreach ($objPapers as $objPaper) {
      $ifArray = array();
      $strItemAssocs = defaultAssocArray();
      $strItemAssocs['line_no']     = $lineNo;
      $strItemAssocs['paper_id']    = encodeText($objPaper->intId);      
      $strItemAssocs['author_id']   = '';
      $strItemAssocs['author_name'] = '';
      $ifArray[] = $objPaper->intStatus;
      if (!empty($objPaper->strFilePath)) {
        $ifArray[] = 5;
      }
      $strItemAssocs['title'] = encodeText($objPaper->strTitle);
      if (!empty($objPaper->fltAvgRating)) {
        $strItemAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 100).'%');
      }
      else {
        $strItemAssocs['avg_rating'] = ' - ';
      }
      $strItemAssocs['last_edited'] = encodeText($objPaper->strLastEdit);
      $strItemAssocs['&popup']      = ($popup ? '&amp;popup' : '');
      $strItemAssocs['if']          = $ifArray;      
      $paperItem = new Template(TPLPATH.'user_paperlistitem.tpl');
      $paperItem->assign($strItemAssocs);
      $paperItem->parse();
      $strPapersAssocs['lines'] .= $paperItem->getOutput();
      $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
    }
  }
  else {
    // Artikelliste ist leer.
    $strItemAssocs = defaultAssocArray();
    $strItemAssocs['colspan'] = '8';
    $strItemAssocs['text'] = 'There are no papers available.';
    $emptyList = new Template(TPLPATH.'empty_list.tpl');
    $emptyList->assign($strItemAssocs);
    $emptyList->parse();
    $strPapersAssocs['lines'] = $emptyList->getOutput();
  }
  $paperList->assign($strPapersAssocs);
  $paperList->parse();
  $strContentAssocs['author_papers'] = $paperList->getOutput();
}
if ($checkChairRole && $objPerson->hasRole(REVIEWER)) {
  $strContentAssocs['if'] = array(1);
}
else {
  $strContentAssocs['if'] = array();
}
$content->assign($strContentAssocs);
$strMainAssocs['content'] = &$content;

if (!$popup) {
  include('./include/usermenu.inc.php');
  $strMainAssocs['menu'] = &$menu;
  $main = new Template(TPLPATH.'frame.tpl');
}
else {
  $main = new Template(TPLPATH.'popup_frame.tpl');
}

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