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

// Lade die Daten der Konferenz
$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference data.', $myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('Conference does not exist in database.', $myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'user_conference.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['if'] = array();
$strContentAssocs['name'] = encodeText($objConference->strName);
$strContentAssocs['description'] = encodeText($objConference->strDescription);
$strContentAssocs['date'] = $objConference->getDateString();
if (!empty($objConference->strHomepage)) {
  $strContentAssocs['if'] = array(1);  	
  $strContentAssocs['link'] = $objConference->strHomepage;
}
else {  
  $strContentAssocs['link'] = 'No homepage available';
}
$strContentAssocs['paper_number'] = encodeText($objConference->intMinNumberOfPapers.' - '.
                                               $objConference->intMaxNumberOfPapers);
$strContentAssocs['abstract_deadline'] = encodeText($objConference->strAbstractDeadline);
$strContentAssocs['paper_deadline'] = encodeText($objConference->strPaperDeadline);
$strContentAssocs['review_deadline'] = encodeText($objConference->strReviewDeadline);
$strContentAssocs['final_deadline'] = encodeText($objConference->strFinalDeadline);
$strContentAssocs['notification'] = encodeText($objConference->strNotification);
$content->assign($strContentAssocs);


$actMenu = 0;
$actMenuItem = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed() || empty($objConference)) {
  error('An error occured during retrieving actual conference!',
        $myDBAccess->getLastError());
}
$strMainAssocs['title'] = 'Conference '.encodeText($objConference->strName);
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Conference';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>