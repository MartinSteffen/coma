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
checkAccess(REVIEWER);

$ifArray = array();
$content = new Template(TPLPATH.'reviewer_start.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['paper_dl']       = '';
$strContentAssocs['review_dl']      = '';
$strContentAssocs['review_no']      = '';
$strContentAssocs['crit_papers_no'] = '';

$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get conference details', $myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('get conference details', 'Conference '.session('confid').' does not exist in database.');
}
$intCriticalPapers = $myDBAccess->getNumberOfCriticalPapers(session('confid'), session('uid'));
if ($myDBAccess->failed()) {
  error('get number of critical papers', $myDBAccess->getLastError());
}
if ($intCriticalPapers > 0) {
  $strContentAssocs['crit_papers_no'] = encodeText($intCriticalPapers);
  $ifArray[] = 5;
}
if (strtotime("now") < strtotime($objConference->strReviewDeadline) &&
    strtotime($objConference->strAbstractDeadline) <= strtotime("now")) {
  $objPapers = $myDBAccess->getPapersOfReviewer(session('uid'), session('confid'));
  if ($myDBAccess->failed()) {
    error('gather list of reviewed papers', $myDBAccess->getLastError());
  }
  $intMissingReviews = 0;
  foreach ($objPapers as $objPaper) {  
    $isReviewed = $myDBAccess->hasPaperBeenReviewed($objPaper->intId, session('uid'));
    if ($myDBAccess->failed()) {
      error('check review status',$myDBAccess->getLastError());
    }
    if (!$isReviewed) {
      $intMissingReviews++;
    }
  }
  if ($intMissingReviews > 0) {
    $strContentAssocs['review_no'] = encodeText($intMissingReviews);
    $strContentAssocs['review_dl'] = encodeText(emptytime(strtotime($objConference->strReviewDeadline)));
    $ifArray[] = 1;
  }
}

$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$_SESSION['menu'] = REVIEWER;
$_SESSION['menuitem'] = 1;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Main tasks for reviewer '.encodeText(session('uname'));
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Reviewer  |  Main';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>