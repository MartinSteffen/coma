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

// Lade die Daten der Reviews des Papers
if (isset($_GET['paperid'])) {
  $objPaper = $myDBAccess->getPaperDetailed($_GET['paperid']);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving paper.', $myDBAccess->getLastError());
  }
  else if (empty($objPaper)) {
    error('Paper does not exist in database.', '');
  }
  $objReviews = $myDBAccess->getReviewsOfPaper($objPaper->intId);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving review report.', $myDBAccess->getLastError());
  }
}
else {
  redirect('chair_reviews.php');
}

$content = new Template(TPLPATH.'chair_paperreviewlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['paper_id'] = encodeText($objPaper->intId);
$strContentAssocs['author_id'] = encodeText($objPaper->intAuthorId);
$strContentAssocs['author_name'] = encodeText($objPaper->strAuthorName);
$strContentAssocs['title'] = encodeText($objPaper->strTitle);
if (!empty($objPaper->fltAvgRating)) {
  $strContentAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 100).'%');
}
else {
  $strContentAssocs['avg_rating'] = ' - ';
}    
// Pruefe noch, ob der reviewte Artikel kritisch ist.
$strContentAssocs['if'] = array();

$content->assign($strContentAssocs);

$actMenu = CHAIR;
$actMenuItem = 4;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Review report for paper';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Reviews for paper';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>