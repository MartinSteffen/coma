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
    $intForumId = $myDBAccess->addForum($objPaper->intConferenceId,
                                        'Discussion of paper \''.$objPaper->strTitle.'\'',
                                        FORUM_PAPER, $objPaper->intId);
    if ($myDBAccess->failed()) {      
      error('Error during creating paper discussion forum.', $myDBAccess->getLastError());
    }
  }
}

?>