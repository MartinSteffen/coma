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

$mainIfArray = array(1);
$strMessage = '';

$objChair = $myDBAccess->getPerson(session('uid'));
if ($myDBAccess->failed()) {
  error('get chair data', $myDBAccess->getLastError());
}
else if (empty($objChair)) {
  error('get chair data', 'Chair does not exist in database!');	
}
$strFrom = '"'.$objChair->getName(2).'" <'.$objChair->strEmail.'>';
$objConference = $myDBAccess->getConferenceDetailed(session('confid'));
if ($myDBAccess->failed()) {
  error('get conference data', $myDBAccess->getLastError());
}
else if (empty($objConference)) {
  error('get conference data', 'Conference does not exist in database!');
}
$strMailAssocs = defaultAssocArray();
$strMailAssocs['chair']      = $objChair->getName(2);
$strMailAssocs['conference'] = $objConference->strName;
$strMailAssocs['review_dl']  = $objConference->strReviewDeadline;

$objPersons = $myDBAccess->getUsersOfConference(session('confid'));
if ($myDBAccess->failed()) {
  error('gather list of users', $myDBAccess->getLastError());
}
foreach ($objPersons as $objPerson) {
  if ($objPerson->hasRole(REVIEWER)) {
    $strMailAssocs['name']        = $objPerson->getName(2);
    $strMailAssocs['paper_lines'] = '';
    $objPapers = $myDBAccess->getPapersOfReviewer($objPerson->intId, session('confid'));
    if ($myDBAccess->failed()) {
      error('gather list of papers of reviewer', $myDBAccess->getLastError());
    }
    foreach ($objPapers as $objPaper) {
      $strMailAssocs['paper_lines'] .= (!empty($strMailAssocs['paper_lines']) ? '<br>' : '');
      $strMailAssocs['paper_lines'] .= '- \''.$objPaper->strTitle.'\' by '.$objPaper->strAuthor;
    }
    $mail = new Template(TPLPATH.'mail_reviewer.tpl');
    $mail->assign($strMailAssocs);
    $mail->parse();
    if (!sendMail($objPerson->intId, 'Papers distributed to you', $mail->getOutput())) {
      $strMessage .= (!empty($strMessage) ? '<br>' : '');
      $strMessage .= 'Failed to send email to reviewer '.$objPerson->getName(2).'!';
      $mainIfArray = array(2);
    }
  }
}

$content = new Template(TPLPATH.'confirm_notify_reviewers.tpl');
$strContentAssocs = defaultAssocArray();
$strContentAssocs['message'] = $strMessage;
$strContentAssocs['if'] = $mainIfArray;
$content->assign($strContentAssocs);

include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Conferences Overview';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  '.
                             (session('menuitem') == 3 ? 'Papers' : 'Reviews');

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>