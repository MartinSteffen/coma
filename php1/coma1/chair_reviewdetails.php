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
$checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), CHAIR);
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference topics.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');	
}

// Lade die Daten des Reviewreports
if (isset($_POST['reviewid'])) {
  $objReview = $myDBAccess->getReviewDetailed($_POST['reviewid']);
  if ($myDBAccess->failed()) {
    error('Error occured during retrieving review.', $myDBAccess->getLastError());
  }
  else if (empty($objReview)) {
    error('Review report does not exist in database.', '');
  }
}
else {
  redirect('chair_reviews.php');
}

$content = new Template(TPLPATH.'view_review.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['paper_id'] = encodeText($objReview->intPaperId);
$strContentAssocs['author_id'] = encodeText($objReview->intAuthorId);
$strContentAssocs['author_name'] = encodeText($objReview->strAuthorName);
$strContentAssocs['reviewer_id'] = encodeText($objReview->intReviewerId);
$strContentAssocs['reviewer_name'] = encodeText($objReview->strReviewerName);
$strContentAssocs['title'] = encodeText($objReview->strPaperTitle);
if (!empty($objReview->fltReviewRating)) {
  $strContentAssocs['rating'] = encodeText(round($objReview->fltReviewRating * 100).'%');
}
else {
  $strContentAssocs['rating'] = ' - ';
}    
if (!empty($objReview->fltAverageRating)) {
  $strContentAssocs['avg_rating'] = encodeText(round($objReview->fltAverageRating * 100).'%');
}
else {
  $strContentAssocs['avg_rating'] = ' - ';
}
// Pruefe noch, ob der reviewte Artikel kritisch ist.
$strContentAssocs['if'] = array();

$strContentAssocs['crit_lines']   = '';
for ($i = 0; $i < count($objReview->objCriterions); $i++) {
  $critForm = new Template(TPLPATH.'view_critlistitem.tpl');
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

include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Review details';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['message']='';
echo $strMainAssocs['message'];

//global $strRoles;
if (isset($_SESSION['menu']) && !empty($_SESSION['menu'])) {
  $strMenu = $strRoles[(int)$_SESSION['menu']];
  if ($_SESSION['menu'] == CHAIR || $_SESSION['menu'] == AUTHOR) {
    $strMenu .= '  |  Papers';
  }
  else if ($_SESSION['menu'] == REVIEWER) {
    $strMenu .= '  |  Reviews';
  }
}
else {
  $strMenu = 'Conference';
}
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  '.$strMenu.'  |  Review details';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>