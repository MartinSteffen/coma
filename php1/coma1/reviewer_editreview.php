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

if (!isset($_GET['reviewid']) && !isset($_POST['reviewid'])) {
  redirect("reviewer_reviews.php");
}
$intReviewId = (isset($_GET['reviewid']) ? $_GET['reviewid'] : $_POST['reviewid']);
// Lade die Daten des Reviews
$objReview = $myDBAccess->getReviewDetailed($intReviewId);
if ($myDBAccess->failed()) {
  error('Error occured during retrieving review.', $myDBAccess->getLastError());
}
else if (empty($objReview)) {
  error('Review does not exist in database.', '');
}

$content = new Template(TPLPATH.'reviewer_editreview.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['review_id'] = encodeText($intReviewId);
$strContentAssocs['paper_id'] = encodeText($objReview->intPaperId);
$strContentAssocs['title'] = encodeText($objReview->strPaperTitle);
$strContentAssocs['author_id'] = encodeText($objReview->intAuthorId);
$strContentAssocs['author_name'] = encodeText($objReview->strAuthorName);
$strContentAssocs['rating'] = encodeText(round($objReview->fltReviewRating * 100));
$strContentAssocs['summary'] = encodeText($objReview->strSummary);
$strContentAssocs['confidential'] = encodeText($objReview->strConfidential);
$strContentAssocs['remarks'] = encodeText($objReview->strRemarks);
$strContentAssocs['crit_lines'] = '';
for ($i = 0; $i < count($objReview->objCriterions); $i++) {
  $critForm = new Template(TPLPATH.'review_critlistitem.tpl');
  $strCritAssocs = defaultAssocArray();
  $strCritAssocs['crit_no']    = encodeText($i+1);
  $strCritAssocs['crit_id']    = encodeText($objReview->objCriterions[$i]->intId);
  $strCritAssocs['crit_name']  = encodeText($objReview->objCriterions[$i]->strName);
  $strCritAssocs['crit_descr'] = encodeText($objReview->objCriterions[$i]->strDescription);
  $strCritAssocs['crit_max']   = encodeText($objReview->objCriterions[$i]->intMaxValue);
  $strCritAssocs['rating']     = encodeText($objReview->intRatings[$i]);
  $strCritAssocs['comment']    = encodeText($objReview->strComments[$i]);
  $critForm->assign($strCritAssocs);
  $critForm->parse();
  $strContentAssocs['crit_lines'] .= $critForm->getOutput();
}

$content->assign($strContentAssocs);

$actMenu = REVIEWER;
$actMenuItem = 2;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Edit review';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Reviewer  |  Reviews';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>