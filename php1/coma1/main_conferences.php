<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

define('IN_COMA1', true);
define('NEED_NO_LOGIN', false);
require_once('./include/header.inc.php');

$content = new Template(TPLPATH.'conference_list.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = '';

$conferenceItem = new Template(TPLPATH.'conference_listitem.tpl');
$strItemAssocs = defaultAssocArray();

$objConferences = $myDBAccess->getAllConferences();
if (!empty($objConferences)) {
  $lineNo = 1;
  foreach ($objConferences as $objConference) {
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['name'] = $objConference->strName;
    $conferenceItem->assign($strItemAssocs);
    $conferenceItem->parse();
    $strContentAssocs['lines'] .= $conferenceItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {
  $strContentAssocs['lines'] = '<tr class="listitem-1"><td colspan="2">'.
                               'Es sind keine Konferenzen vorhanden.</td></tr>';  
}

$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'mainmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['menu'] = array(1, 2, 3, 4);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Liste aller Konferenzen';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;

$strPath = array('CoMa'=>$_SESSION['uname'], 'Konferenzen'=>'');
require_once(TPLPATH.'navigatoritem.php');
$strMainAssocs['navigator'] = createNavigatorContent($strPath);

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>