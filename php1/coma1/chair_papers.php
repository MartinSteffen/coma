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
checkAccess(CHAIR);

if (isset($_POST['action'])) {
  if ($_POST['action'] == 'delete') {
    if (empty($_POST['confirm_delete'])) {
      $strMessage = 'You have to check the delete confirm option!';
    }
    else {
      $myDBAccess->deletePaper($_POST['paperid']);
      if ($myDBAccess->failed()) {
        error('deleting paper', $myDBAccess->getLastError());
      }
    }
  }
  else if ($_POST['action'] == 'changestatus') {
    $myDBAccess->setPaperStatus($_POST['paperid'],
      ($_POST['submit'] == 'accept' ? PAPER_ACCEPTED : PAPER_REJECTED));
    if ($myDBAccess->failed()) {
      error('updating paper status', $myDBAccess->getLastError());
    }
  }
  else if ($_POST['action'] == 'resetstatus') {
    $myDBAccess->resetPaperStatus($_POST['paperid']);
    if ($myDBAccess->failed()) {
      error('resetting paper status', $myDBAccess->getLastError());
    }
  }
}

if (isset($_GET['order'])) {
  if ((int)session('orderpapers', false) != $_GET['order']) {
    $_SESSION['orderpapers'] = $_GET['order'];
  }
  else {
    unset($_SESSION['orderpapers']);
  }
}
$intOrder = (int)session('orderpapers', false);
$ifArray = array($intOrder);

$objPapers = $myDBAccess->getPapersOfConference(session('confid'), $intOrder);
if ($myDBAccess->failed()) {
  error('gather list of papers for chair', $myDBAccess->getLastError());
}

$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get conference details',$myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('get conference details', 'Conference '.session('confid').' does not exist in database.');
}

$content = new Template(TPLPATH.'chair_paperlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = '';
$strMessage = session('message', false);
session_delete('message');
if (!empty($strMessage)) {
  $strContentAssocs['message'] = $strMessage;
  $ifArray[] = 9;
}
$strContentAssocs['if'] = $ifArray;
$strContentAssocs['targetpage'] = 'chair_papers.php';
// Pruefe ob die Reviewphase begonnen oder beendet ist
if (strtotime($objConference->strAbstractDeadline) <= strtotime("now") &&
    strtotime("now") < strtotime($objConference->strReviewDeadline)) {
  $ifArray[] = 'REVIEWERNOTIFY';
}
else if (strtotime($objConference->strReviewDeadline) <= strtotime("now") &&
         strtotime("now") <= strtotime($objConference->strNotification)) {
  $ifArray[] = 'AUTHORNOTIFY';
}

$strContentAssocs['lines'] = '';
if (!empty($objPapers)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
    $strItemAssocs = defaultAssocArray();
    $ifArray = array();
    $strItemAssocs['line_no'] = $lineNo;
    $strItemAssocs['paper_id'] = encodeText($objPaper->intId);
    $strItemAssocs['author_id'] = encodeText($objPaper->intAuthorId);
    $strItemAssocs['author_name'] = encodeText($objPaper->strAuthor);
    $ifArray[] = $objPaper->intStatus;
    if (!empty($objPaper->strFilePath)) {
      $ifArray[] = 5;
    }
    $strItemAssocs['title'] = encodeText($objPaper->strTitle);
    $intRevs = $myDBAccess->getNumberOfReviewsOfPaper($objPaper->intId);
    if ($myDBAccess->failed()) {
      error('gather list of reviews for chair', $myDBAccess->getLastError());
    }
    $objReviewers = $myDBAccess->getReviewersOfPaper($objPaper->intId);
    if ($myDBAccess->failed()) {
      error('gather list of reviews for chair', $myDBAccess->getLastError());
    }
    $strItemAssocs['num_reviews'] = encodeText($intRevs.' of '.count($objReviewers));
    if ($intRevs > 0) {
      $strItemAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 100).'%');
    }
    else {
      $strItemAssocs['avg_rating'] = ' - ';
    }
    $fltVariance = $myDBAccess->getVarianceOfPaper($objPaper->intId);
    if ($myDBAccess->failed()) {
      error('get paper list of chair', $myDBAccess->getLastError());
    }
    if (!empty($fltVariance) || is_numeric($fltVariance)) {
      $strItemAssocs['variance'] = encodeText(round($fltVariance * 100).'%');
      if ($fltVariance >= $objConference->fltCriticalVariance) {
        $ifArray[] = 6;
      }
      else {
        $ifArray[] = 7;
      }
    }
    else {
      $strItemAssocs['variance'] = ' - ';
      $ifArray[] = 7;
    }
    if ($objPaper->intStatus != PAPER_ACCEPTED) {
      $ifArray[] = 8;
    }
    $strItemAssocs['last_edited'] = encodeText($objPaper->strLastEdit);
    $strItemAssocs['if'] = $ifArray;
    $paperItem = new Template(TPLPATH.'chair_paperlistitem.tpl');
    $paperItem->assign($strItemAssocs);
    $paperItem->parse();
    $strContentAssocs['lines'] .= $paperItem->getOutput();
    $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
  }
}
else {
  // Artikelliste ist leer.
  $strItemAssocs = defaultAssocArray();
  $strItemAssocs['colspan'] = '8';
  $strItemAssocs['text'] = 'There are no papers available.';
  $emptyList = new Template(TPLPATH.'empty_list.tpl');
  $emptyList->assign($strItemAssocs);
  $emptyList->parse();
  $strContentAssocs['lines'] = $emptyList->getOutput();
}

$content->assign($strContentAssocs);

$_SESSION['menu'] = CHAIR;
$_SESSION['menuitem'] = 3;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Manage papers';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>
