<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 * @todo rework completly
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
$strContentAssocs['lines'] = '';  
if (!empty($objConferences)) {
  $lineNo = 1;
  $strContentAssocs['if'] = array();  
  foreach ($objConferences as $objConference) {
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['confid'] = $objConference->intId;
    $strItemAssocs['name'] = $objConference->strName;
    $strItemAssocs['startdate'] = '';//$objConference->strStart;
    $strItemAssocs['enddate'] = '';//$objConference->strEnd;
    $strItemAssocs['if'] = array(1, 2, 3);  
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

$strPath = array(session('uname')=>'', 'Conferences'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>