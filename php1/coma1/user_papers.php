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
$showAuthorPapers = isset($_GET['showauthorpapers']);
$showAcceptedPapers = isset($_GET['showacceptedpapers']);
if ($showAuthorPapers) {
  $showAuthorPapers = true;
  $intAuthorId = $_GET['authorid'];
  $checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), CHAIR) ||
               (session('uid') == $intAuthorId);
}
else if ($showAcceptedPapers) {
  $checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'));
}
else {
 $checkRole = $myDBAccess->hasRoleInConference(session('uid'), session('confid'), CHAIR);
}
if ($myDBAccess->failed()) {
  error('Error occured during retrieving conference data.', $myDBAccess->getLastError());
}
else if (!$checkRole) {
  error('You have no permission to view this page.', '');
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
  error('get paper list',$myDBAccess->getLastError());
}

$content = new Template(TPLPATH.'user_paperlist.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['targetpage'] = 'user_papers.php';
$strContentAssocs['if']         = $ifArray;
$strContentAssocs['lines']      = '';
$strContentAssocs['&option']    = '';
$strContentAssocs['&option']   .= ($showAuthorPapers   ? '&amp;showauthorpapers'   : '');
$strContentAssocs['&option']   .= ($showAcceptedPapers ? '&amp;showacceptedpapers' : '');
$paperCount = 0;
if (!empty($objPapers)) {
  $lineNo = 1;
  foreach ($objPapers as $objPaper) {
    if (!($showAcceptedPapers && $objPaper->intStatus != PAPER_ACCEPTED) &&
        !($showAuthorPapers   && $objPaper->intAuthorId != $intAuthorId)) {
      $ifArray = array();
      $strItemAssocs = defaultAssocArray();
      $strItemAssocs['line_no'] = $lineNo;
      $strItemAssocs['paper_id'] = encodeText($objPaper->intId);
      $strItemAssocs['author_id'] = encodeText($objPaper->intAuthorId);
      if (!empty($objPaper->intAuthorId)) {
        $ifArray[] = 6;
      }
      $strItemAssocs['author_name'] = encodeText($objPaper->strAuthor);
      $ifArray[] = $objPaper->intStatus;
      if (!empty($objPaper->strFilePath)) {
        $ifArray[] = 5;
      }
      $strItemAssocs['title'] = encodeText($objPaper->strTitle);
      if (!empty($objPaper->fltAvgRating)) {
        $strItemAssocs['avg_rating'] = encodeText(round($objPaper->fltAvgRating * 100).'%');
      }
      else {
        $strItemAssocs['avg_rating'] = ' - ';
      }
      $strItemAssocs['last_edited'] = encodeText($objPaper->strLastEdit);
      $strItemAssocs['&popup']      = '';
      $strItemAssocs['if']          = $ifArray;      
      $paperItem = new Template(TPLPATH.'user_paperlistitem.tpl');
      $paperItem->assign($strItemAssocs);
      $paperItem->parse();
      $strContentAssocs['lines'] .= $paperItem->getOutput();
      $lineNo = 3 - $lineNo;  // wechselt zwischen 1 und 2
      $paperCount++;
    }
  }
}
if (empty($objPapers) || $paperCount == 0) {
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

$_SESSION['menu'] = 0;
$_SESSION['menuitem'] = 8;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
if ($showAuthorPapers) {
  $strMainAssocs['title'] = 'Papers of author';
}
else if ($showAcceptedPapers) {
  $strMainAssocs['title'] = 'Accepted papers for conference';
}
else {
  $strMainAssocs['title'] = 'All papers in conference';
}

$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Conference  |  All papers';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>