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
checkAccess(AUTHOR);

$ifArray = array();
$content = new Template(TPLPATH.'author_start.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['abstract_dl']      = '';
$strContentAssocs['paper_dl']         = '';
$strContentAssocs['final_dl']         = '';
$strContentAssocs['papers_wo_upload'] = '';

$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get conference details',$myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('conference '.session('confid').' does not exist in database.','');
}
// Pruefe ob die letzte Woche vor der Abstract-Deadline erreicht worden ist
if (strtotime($objConference->strAbstractDeadline) <= strtotime("-1week")) {
  $strContentAssocs['abstract_dl'] = encodeText(emptytime(strtotime($objConference->strAbstratcDeadline)));
  $ifArray[] = 2;
}
// Pruefe ob die Paper-Deadline noch nicht erreicht worden ist
if (strtotime("now") < strtotime($objConference->strPaperDeadline)) {
  $ifArray[] = 0;
  // Pruefe ob die letzte Woche vor der Paper-Deadline erreicht worden ist
  if (strtotime($objConference->strPaperDeadline) <= strtotime("-1week")) {
    $numOfPapersWithoutUpload = $myDBAccess->numOfPapersOfAuthorWithoutUpload(session('uid'), session('confid'));
    if ($numOfPapersWithoutUpload > 0) {
      $strContentAssocs['papers_wo_upload'] = encodeText($numOfPapersWithoutUpload);
      $strContentAssocs['paper_dl'] = encodeText(emptytime(strtotime($objConference->strPaperDeadline)));
      $ifArray[] = 3;
    }
  }
}
// Pruefe ob die Final-Deadline noch nicht erreicht worden ist
if (strtotime("now") < strtotime($objConference->strFinalDeadline)) {
  $ifArray[] = 1;  
  // Pruefe ob die letzte Woche vor der Final-Deadline erreicht worden ist
  if (strtotime($objConference->strFinalDeadline) <= strtotime("-1week")) {
    $strContentAssocs['final_dl'] = encodeText(emptytime(strtotime($objConference->strFinalDeadline)));
    $ifArray[] = 4;
  }
}

$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$_SESSION['menu'] = AUTHOR;
$_SESSION['menuitem'] = 1;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Main tasks for author '.encodeText(session('uname'));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Author  |  Main';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>