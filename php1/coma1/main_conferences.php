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

$strMessage = session('message', false);
session_delete('message');
$strContentAssocs['message'] = $strMessage;
if (!empty($strMessage)) {  
  $strContentAssocs['if'] = array(9);
}
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
      if (!($objPerson->hasRole(REVIEWER)) && !($objPerson->hasRequestedRole(REVIEWER))) {
        $ifArray[] = 2;
      }
      else if ($objPerson->hasRequestedRole(REVIEWER)) {
        $ifArray[] = 6;
      }
      if (!($objPerson->hasRole(AUTHOR)) && !($objPerson->hasRequestedRole(AUTHOR))) {
        $ifArray[] = 3;
      }
      else if ($objPerson->hasRequestedRole(AUTHOR)) {
        $ifArray[] = 7;
      }
      if (!($objPerson->hasRole(PARTICIPANT)) && !($objPerson->hasRequestedRole(PARTICIPANT))) {
        $ifArray[] = 4;
      }      
    }
    $strItemAssocs['line_no'] = encodeText($lineNo);
    $strItemAssocs['confid'] = encodeText($objConference->intId);
    $strItemAssocs['roleid_author'] = encodeText(AUTHOR);
    $strItemAssocs['roleid_reviewer'] = encodeText(REVIEWER);
    $strItemAssocs['roleid_participant'] = encodeText(PARTICIPANT);
    $strItemAssocs['link'] = encodeURL($objConference->strHomepage);
    if (!empty($objConference->strHomepage)) {
      $ifArray[] = 5;
    }
    $strItemAssocs['name'] = encodeText($objConference->strName);
    $strItemAssocs['date'] = encodeText($objConference->getDateString());
    $strItemAssocs['if'] = $ifArray;
    $conferenceItem = new Template(TPLPATH.'conference_listitem.tpl');
    $conferenceItem->assign($strItemAssocs);
    $conferenceItem->parse();
    $strContentAssocs['lines'] .= $conferenceItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {  
  $strItemAssocs = defaultAssocArray();
  $strItemAssocs['colspan'] = '3';
  $strItemAssocs['text'] = 'There are no users available.';
  $emptyList = new Template(TPLPATH.'empty_list.tpl');
  $emptyList->assign($strItemAssocs);
  $emptyList->parse();
  $strContentAssocs['lines'] = $emptyList->getOutput();  
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