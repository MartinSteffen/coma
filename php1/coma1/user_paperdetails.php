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

// Lade die Daten des Artikels
if (isset($_GET['paperid'])) {
  $objPaper = $myDBAccess->getPaperDetailed($_GET['paperid']);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving paper.', $myDBAccess->getLastError());
  }
  else if (empty($objPaper)) {
    error('Paper does not exist in database.', '');
  }
}
else {
  redirect('user_papers.php');
}

$content = new Template(TPLPATH.'view_paper.tpl');
$strContentAssocs = defaultAssocArray();
$ifArray = array();
//$ifArray[] = $objPaper->intStatus;
$strContentAssocs['paper_id'] = $objPaper->intId;
$strContentAssocs['title'] = encodeText($objPaper->strTitle);
$strContentAssocs['abstract'] = encodeText($objPaper->strAbstract);
$strContentAssocs['author_id'] = $objPaper->intAuthorId;
$strContentAssocs['author_name'] = encodeText($objPaper->strAuthor);      
$strContentAssocs['file_link'] = encodeURL($objPaper->strFilePath);
$strContentAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 10) / 10);
$strContentAssocs['last_edited'] = encodeText(emptytime(strtotime($objPaper->strLastEdit), 'Y-m-d'));
$strContentAssocs['version'] = encodeText($objPaper->intVersion);
$strContentAssocs['coauthors'] = '';
for ($i = 0; $i < count($objPaper->strCoAuthors); $i++) {
  if ($i > 0) {
    $strContentAssocs['coauthors'] .= ', ';
  }
  $strContentAssocs['coauthors'] .= $objPaper->strCoAuthors[$i];
}
$strContentAssocs['topics'] = '';
for ($i = 0; $i < count($objPaper->objTopics); $i++) {
  if ($i > 0) {
    $strContentAssocs['topics'] .= ', ';
  }
  $strContentAssocs['topics'] .= $objPaper->objTopics[$i]->strName;
}

if (!empty($objPaper->strFilePath)) {
  $ifArray[] = 5;
}
else {
  $ifArray[] = 6;
}
$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$actMenu = 0;
$actMenuItem = 7;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Details of paper';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>