<?php
/**
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}

/**
 * Pruefe, ob ein Forum zu dem Paper $intPaperId existiert, wenn dieses
 * kritisch bewertet wurde, und eroeffne die Diskussion, falls dieses noch
 * nicht passiert ist und falls das entsprechende Flag in der Konferenz-
 * konfiguration gesetzt ist.
 * Gibt true zurueck, wenn das Forum erzeugt wurde,
 * false, wenn das Forum bereits existiert oder das Paper nicht kritisch ist.
 */
 
function createPaperForumIfCritical(&$myDBAccess, $intPaperId) {
  $objConference = $myDBAccess->getConferenceDetailed(session('confid'));
  if ($myDBAccess->failed()) {
    error('get conference configuration', $myDBAccess->getLastError());
  }
  else if (empty($objConference)) {
    error('get conference configuration', 'Conference '.session('confid').' does not exist in database!');
  }
  $isCritical = $myDBAccess->isPaperCritical($intPaperId);
  if ($myDBAccess->failed()) {
    error('check paper status', $myDBAccess->getLastError());
  }
  if ($isCritical && $objConference->blnAutoOpenPaperForum) {
    return createPaperForum($myDBAccess, $intPaperId);
  }
  else {
    return false;
  }
}

/**
 * Erzeugt ein Forum zum Paper $intPaperId.
 * Gibt true zurueck, wenn das Forum erzeugt wurde,
 * false, wenn das Forum bereits existiert.
 */
 
function createPaperForum(&$myDBAccess, $intPaperId) {
  $objPaper = $myDBAccess->getPaperDetailed($intPaperId);
  if ($myDBAccess->failed()) {
    error('get paper to create forum for', $myDBAccess->getLastError());
  }
  else if (empty($objPaper)) {
    error('create forum of paper','Paper '.$intPaperId.' does not exist in database!');
  }
  $objPaperForum = $myDBAccess->getForumOfPaper($intPaperId);
  if ($myDBAccess->failed()) {
    error('get forum of paper', $myDBAccess->getLastError());
  }
  // Erzeuge ein Forum zum Paper und benachrichtige die Reviewer, wenn es noch keines gibt
  if (empty($objPaperForum)) {
    $intForumId = $myDBAccess->addForum(session('confid'),
                                        'Discussion of paper \''.$objPaper->strTitle.'\'',
                                        FORUM_PAPER, $objPaper->intId);
    if ($myDBAccess->failed()) {      
      error('create paper forum.', $myDBAccess->getLastError());
    }
    $objReviewers = $myDBAccess->getReviewersOfPaper($intPaperId);
    if ($myDBAccess->failed()) {      
      error('Error during receiving reviewers of paper.', $myDBAccess->getLastError());
    }
    $objChair = $myDBAccess->getPerson(session('uid'));
    if ($myDBAccess->failed()) {
      error('retrieve chair data', $myDBAccess->getLastError());
    }
    $strFrom = '"'.$objChair->getName(2).'" <'.$objChair->strEmail.'>';    
    $objConference = $myDBAccess->getConferenceDetailed(session('confid'));
    if ($myDBAccess->failed()) {
      error('retrieve conference data', $myDBAccess->getLastError());
    }        
    $strMailAssocs = defaultAssocArray();
    $strMailAssocs['chair'] = $objChair->getName(2);    
    $strMailAssocs['conference'] = $objConference->strName;
    $strMailAssocs['paper'] = $objPaper->strTitle;
    foreach ($objReviewers as $objReviewer) {
      $strMailAssocs['name'] = $objReviewer->getName(2);
      $mail = new Template(TPLPATH.'mail_startdiscussion.tpl');
      $mail->assign($strMailAssocs);
      $mail->parse();
      sendMail($objReviewer->intId, "Discussion of paper '".$objPaper->strTitle."' started.",
               $mail->getOutput(), $strFrom);
    }    
    return true;
  }
  // HIER IST DER BOESE BUG DER PRAESENTATION!
  //echo($objPaperForum);
  return false;
}

?>