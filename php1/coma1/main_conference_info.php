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
if (isset($_GET['confid'])) {
  $objConference = $myDBAccess->getConferenceDetailed($_GET['confid']);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving conference data.', $myDBAccess->getLastError());
  }
  else if (empty($objConference)) {
    error('Conference does not exist in database.', $myDBAccess->getLastError());
  }
}
else {
  redirect('main_conferences.php');
}

$content = new Template(TPLPATH.'conference_info.tpl');
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
  strContentAssocs['link'] = 'No homepage available';
}
$strContentAssocs['paper_number'] = encodeText($objConference->intMinNumberOfPapers.' - '.
                                               $objConference->intMaxNumberOfPapers);
$strContentAssocs['abstract_deadline'] = encodeText($objConference->strAbstractDeadline);
$strContentAssocs['paper_deadline'] = encodeText($objConference->strPaperDeadline);
$strContentAssocs['review_deadline'] = encodeText($objConference->strReviewDeadline);
$strContentAssocs['final_deadline'] = encodeText($objConference->strFinalDeadline);
$strContentAssocs['notification'] = encodeText($objConference->strNotification);
$strContentAssocs['return_page'] = 'main_conferences.php';
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'mainmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(2);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Conference Description';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Conferences';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>