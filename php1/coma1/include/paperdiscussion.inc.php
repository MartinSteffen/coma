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
 * nicht passiert ist.
 */

$objPaper = $myDBAccess->getPaperDetailed($intPaperId);
if ($myDBAccess->failed()) {
  error('Error occured retrieving paper.', $myDBAccess->getLastError());
}
else if (empty($objPaper)) {
  error('Test paper discussion','Paper '.$intPaperId.' does not exist in database!');
}

// Pruefe ob ein Forum existiert, wenn das Paper kritisch bewertet ist.
if ($objPaper->intStatus == PAPER_CRITICAL) {
  $objPaperForum = $myDBAccess->getForumOfPaper($intPaperId);
  if ($myDBAccess->failed()) {
    error('Error occured retrieving forum of paper.', $myDBAccess->getLastError());
  }
  // Erzeuge ein Forum zum Paper und benachrichtige die Reviewer
  if (empty($objPaperForum)) {
    $intForumId = $myDBAccess->addForum(session('confid'),
                                        'Discussion of paper \''.$objPaper->strTitle.'\'',
                                        FORUM_PAPER, $objPaper->intId);
    if ($myDBAccess->failed()) {      
      error('Error during creating paper discussion forum.', $myDBAccess->getLastError());
    }
    $objReviewers = $myDBAccess_>getReviewersOfPaper($intPaperId);
    if ($myDBAccess->failed()) {      
      error('Error during receiving reviewers of paper.', $myDBAccess->getLastError());
    }
    $objChair = $myDBAccess->getPerson(session('uid'));
    if ($myDBAccess->failed()) {
      error('Error retrieving chair data', $myDBAccess->getLastError());
    }
    $strFrom = '"'.$objChair->getName(2).'" <'.$objChair->strEmail.'>';    
    $objConference = $myDBAccess->getConferenceDetailed(session('confid'));
    if ($myDBAccess->failed()) {
      error('Error retrieving conference data', $myDBAccess->getLastError());
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
    $strMessage .= '<br>The paper was marked as critical. A discussion forum for this '.
                   'paper has been opened.';
  }
}

?>