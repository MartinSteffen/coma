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
require_once('./include/paperdiscussion.inc.php');

// Pruefe Zugriffsberechtigung auf die Seite
checkAccess(REVIEWER);

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
// Pruefe ob Review zur akt. Konferenz gehoert
checkPaper($objReview->intPaperId);

// Aktualisiere Review mit den mitgeschickten Daten
if (isset($_POST['action'])) {
  $objReview->strSummary      = $_POST['summary'];
  $objReview->strRemarks      = $_POST['remarks'];
  $objReview->strConfidential = $_POST['confidential'];
  for ($i = 0; $i < count($objReview->intRatings); $i++) {
    $objReview->intRatings[$i]  = (int)$_POST['rating-'.($i+1)];
    $objReview->strComments[$i] = $_POST['comment-'.($i+1)];
  }
  // Teste Gueltigkeit der Daten
  $noError = true;
  for ($i = 0; $i < count($objReview->intRatings) && $noError; $i++) {
    if ($objReview->intRatings[$i] < 0 ||
        $objReview->intRatings[$i] > $objReview->objCriterions[$i]->intMaxValue) {
      $noError = false;
      $strMessage = 'There are invalid ratings. Please check if all ratings are '.
                    'within their respective range.';
    }
  }
  if ($noError) {
    $objReview->recalcRating();
    // Uebernimm die Aenderungen
    if (isset($_POST['submit']) ) {
      // Trage Review in die Datenbank ein
      $result = $myDBAccess->updateReviewReport($objReview);
      if (!empty($result)) {
      	$strMessage = 'Review report was updated successfully.';
      	$intPaperId = $objReview->intPaperId;      	
      	$startForum = createPaperForumIfCritical($myDBAccess, $intPaperId);
      	if ($startForum) {
            $strMessage .= '<br>The paper was marked as critical. A discussion forum for this '.
                           'paper has been opened.';
        }
      }
      else if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error during updating review report.', $myDBAccess->getLastError());
      }
    }
  }
}

$content = new Template(TPLPATH.'edit_review.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = '';
$strContentAssocs['targetpage']   = "reviewer_editreview.php";
$strContentAssocs['review_id']    = encodeText($intReviewId);
$strContentAssocs['paper_id']     = encodeText($objReview->intPaperId);
$strContentAssocs['title']        = encodeText($objReview->strPaperTitle);
$strContentAssocs['author_id']    = encodeText($objReview->intAuthorId);
$strContentAssocs['author_name']  = encodeText($objReview->strAuthorName);
$strContentAssocs['rating']       = encodeText(round($objReview->fltReviewRating * 100));
$strContentAssocs['summary']      = encodeText($objReview->strSummary, false);
$strContentAssocs['confidential'] = encodeText($objReview->strConfidential, false);
$strContentAssocs['remarks']      = encodeText($objReview->strRemarks, false);
$strContentAssocs['crit_lines']   = '';
for ($i = 0; $i < count($objReview->objCriterions); $i++) {
  $critForm = new Template(TPLPATH.'review_critlistitem.tpl');
  $strCritAssocs = defaultAssocArray();
  $strCritAssocs['crit_no']    = encodeText($i+1);
  $strCritAssocs['crit_id']    = encodeText($objReview->objCriterions[$i]->intId);
  $strCritAssocs['crit_name']  = encodeText($objReview->objCriterions[$i]->strName);
  $strCritAssocs['crit_descr'] = encodeText($objReview->objCriterions[$i]->strDescription);
  $strCritAssocs['crit_max']   = encodeText($objReview->objCriterions[$i]->intMaxValue);
  $strCritAssocs['rating']     = encodeText($objReview->intRatings[$i]);
  $strCritAssocs['comment']    = encodeText($objReview->strComments[$i], false);
  if ($objReview->intRatings[$i] < 0 ||
      $objReview->intRatings[$i] > $objReview->objCriterions[$i]->intMaxValue) {
    $strCritAssocs['if'] = array(1);
  }
  $critForm->assign($strCritAssocs);
  $critForm->parse();
  $strContentAssocs['crit_lines'] .= $critForm->getOutput();
}
if (isset($_SESSION['message'])) {
  $strMessage = session('message', false);
  unset($_SESSION['message']);
}
if (isset($strMessage) && !empty($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $strContentAssocs['if'] = array(1, 9);
}
else {
  $strContentAssocs['if'] = array(1);
}
$content->assign($strContentAssocs);

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