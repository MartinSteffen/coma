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

// Lade die Daten der Konferenz
$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference data.', $myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('Conference does not exist in database.', '');
}

$content = new Template(TPLPATH.'user_conference.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['name'] = encodeText($objConference->strName);
$strContentAssocs['description'] = encodeText($objConference->strDescription);
$strContentAssocs['date'] = encodeText($objConference->getDateString());
if (!empty($objConference->strHomepage)) {
  $strContentAssocs['link'] = encodeURL($objConference->strHomepage);
  $strContentAssocs['if'] = array(1);
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


$_SESSION['menu'] = 0;
$_SESSION['menuitem'] = 2;
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