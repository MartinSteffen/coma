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

$content = new Template(TPLPATH.'conference_list.tpl');
$strContentAssocs = defaultAssocArray();

$conferenceItem = new Template(TPLPATH.'conference_listitem.tpl');
$strItemAssocs = defaultAssocArray();

$objConferences = $myDBAccess->getAllConferences();
if ($myDBAccess->failed()) {
  error('get conference list',$myDBAccess->getLastError());
}

$strContentAssocs['message'] = session('message', false);
session_delete('message');
$strContentAssocs['if'] = array();
$strContentAssocs['lines'] = '';
if (!empty($objConferences)) {
  $lineNo = 1;
  foreach ($objConferences as $objConference) {
    $objPerson = $myDBAccess->getPerson(session('uid'), $objConference->intId);
    if ($myDBAccess->failed()) {
      error('An error occured during processing the conference list!',
            $myDBAccess->getLastError());
    }
    $ifArray = array();
    if (!empty($objPerson)) {
      if ($objPerson->hasAnyRole()) {
        $ifArray[] = 1;
      }
      if (!($objPerson->hasRole(AUTHOR))) {
        $ifArray[] = 2;
      }
      if (!($objPerson->hasRole(PARTICIPANT))) {
        $ifArray[] = 3;
      }
    }
    $strItemAssocs['line_no'] = encodeText($lineNo);
    $strItemAssocs['confid'] = encodeText($objConference->intId);
    $strItemAssocs['link'] = encodeURL($objConference->strHomepage);
    if (!empty($objConference->strHomepage)) {
      $ifArray[] = 4;
    }
    $strItemAssocs['name'] = encodeText($objConference->strName);
    $strItemAssocs['date'] = $objConference->getDateString();
    $strItemAssocs['if'] = $ifArray;
    $conferenceItem = new Template(TPLPATH.'conference_listitem.tpl');
    $conferenceItem->assign($strItemAssocs);
    $conferenceItem->parse();
    $strContentAssocs['lines'] .= $conferenceItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
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