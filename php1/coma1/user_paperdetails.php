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
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'));
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');	
}

$popup = (isset($_GET['popup'])) ? true : false;

// Lade die Daten des Artikels
if (isset($_GET['paperid']) || isset($_POST['paperid'])) {
  $intPaperId = (isset($_GET['paperid']) ? $_GET['paperid'] : $_POST['paperid']);
  $objPaper = $myDBAccess->getPaperDetailed($intPaperId);
  if ($myDBAccess->failed()) {
    error('Error occured retrieving paper.', $myDBAccess->getLastError());
  }
  else if (empty($objPaper)) {
    error('Display PaperDetailed','Paper '.$intPaperId.' does not exist in database!');
  }
}
else {
  error('No paper selected!', '');
}

$content = new Template(TPLPATH.'view_paper.tpl');
$strContentAssocs = defaultAssocArray();
$ifArray = array();
//$ifArray[] = $objPaper->intStatus;
$strContentAssocs['paper_id'] = encodeText($objPaper->intId);
$strContentAssocs['title'] = encodeText($objPaper->strTitle);
$strContentAssocs['abstract'] = encodeText($objPaper->strAbstract);
$strContentAssocs['author_id'] = encodeText($objPaper->intAuthorId);
$strContentAssocs['author_name'] = encodeText($objPaper->strAuthor);      
$strContentAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 100).'%');
$strContentAssocs['last_edited'] = encodeText($objPaper->strLastEdit);
$strContentAssocs['version'] = encodeText($objPaper->intVersion);
$strContentAssocs['coauthors'] = '';
$strContentAssocs['navlink'] = ($popup) ? array( 'CLOSE' ) : array( 'BACK' );

for ($i = 0; $i < count($objPaper->strCoAuthors); $i++) {
  if ($i > 0) {
    $strContentAssocs['coauthors'] .= ', ';
  }
  $strContentAssocs['coauthors'] .= encodeText($objPaper->strCoAuthors[$i]);
}
$strContentAssocs['topics'] = '';
for ($i = 0; $i < count($objPaper->objTopics); $i++) {
  if ($i > 0) {
    $strContentAssocs['topics'] .= ', ';
  }
  $strContentAssocs['topics'] .= encodeText($objPaper->objTopics[$i]->strName);
}

if (!empty($objPaper->strFilePath)) {
  $ifArray[] = 5;
}
else {
  $ifArray[] = 6;
}
if ($objPaper->intStatus == PAPER_ACCEPTED) {
  $ifArray[] = 7;
}

$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Details of paper';
$strMainAssocs['content'] = &$content;

if (!$popup) {
  include('./include/usermenu.inc.php');
  $strMainAssocs['menu'] = &$menu;
  $main = new Template(TPLPATH.'frame.tpl');
}
else {
  $main = new Template(TPLPATH.'popup_frame.tpl');
}

if (isset($_SESSION['menu']) && !empty($_SESSION['menu'])) {
  $strMenu = $strRoles[(int)$_SESSION['menu']];
}
else {
  $strMenu = 'Conference';
}
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  '.$strMenu.'  |  Paper details';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>