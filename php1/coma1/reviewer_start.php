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
$strContentAssocs['paper_dl'] = '';
$strContentAssocs['review_dl'] = '';
$strContentAssocs['crit_papers_no'] = '';

$intCriticalPapers = $myDBAccess->getNumberOfCriticalPapers(session('confid'), session('uid'));
if ($myDBAccess->failed()) {
  error('get num of critical papers',$myDBAccess->getLastError());
}
if ($intCriticalPapers > 0) {
  $strContentAssocs['crit_papers_no'] = encodeText($intCriticalPapers);
  $ifArray[] = 5;
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