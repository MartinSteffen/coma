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

$content = new Template(TPLPATH.'edit_conference.tpl');
$strContentAssocs = defaultAssocArray();

$ifArray = array();

// Teste, ob Daten mit der Anfrage des Benutzer mitgeliefert wurde.
if (isset($_POST['action']) && !isset($_POST['simple_config_adv'])) {
  // Konvertiere Zeit-Daten in was sinnvolles
  $abstract_dl = empty($_POST['abstract_dl']) ? '' : strtotime($_POST['abstract_dl']);
  $paper_dl = empty($_POST['paper_dl']) ? '' : strtotime($_POST['paper_dl']);
  $review_dl = empty($_POST['review_dl']) ? '' : strtotime($_POST['review_dl']);
  $final_dl = empty($_POST['final_dl']) ? '' : strtotime($_POST['final_dl']);
  $notification = empty($_POST['notification']) ? '' : strtotime($_POST['notification']);
  $start_date = empty($_POST['start_date']) ? '' : strtotime($_POST['start_date']);
  $end_date = empty($_POST['end_date']) ? '' : strtotime($_POST['end_date']);

  // als Eingabe sind nur Interger-Werte erlaubt
  $min_reviews = (int) $_POST['min_reviews'];
  $def_reviews = (int) $_POST['def_reviews'];
  $min_papers  = (int) $_POST['min_papers'];
  $max_papers  = (int) $_POST['max_papers'];
  $variance    = (int) $_POST['variance'];
  $auto_numrev = (int) $_POST['auto_numreviewer'];

  $strContentAssocs['name']             = encodeText($_POST['name']);
  $strContentAssocs['description']      = encodeText($_POST['description']);
  $strContentAssocs['homepage']         = encodeURL($_POST['homepage']);
  $strContentAssocs['start_date']       = encodeText(emptytime($start_date));
  $strContentAssocs['end_date']         = encodeText(emptytime($end_date));
  $strContentAssocs['abstract_dl']      = encodeText(emptytime($abstract_dl));
  $strContentAssocs['paper_dl']         = encodeText(emptytime($paper_dl));
  $strContentAssocs['review_dl']        = encodeText(emptytime($review_dl));
  $strContentAssocs['final_dl']         = encodeText(emptytime($final_dl));
  $strContentAssocs['notification']     = encodeText(emptytime($notification));
  $strContentAssocs['min_reviews']      = encodeText($_POST['min_reviews']);
  $strContentAssocs['def_reviews']      = encodeText($_POST['def_reviews']);
  $strContentAssocs['min_papers']       = encodeText($_POST['min_papers']);
  $strContentAssocs['max_papers']       = encodeText($_POST['max_papers']);
  $strContentAssocs['variance']         = encodeText(intval($_POST['variance']));
  $strContentAssocs['auto_numreviewer'] = encodeText($_POST['auto_numreviewer']);
  if (isset($_POST['auto_actaccount']) && !empty($_POST['auto_actaccount'])) {
    $ifArray[] = 2;
  }
  if (isset($_POST['auto_paperforum']) && !empty($_POST['auto_paperforum'])) {
    $ifArray[] = 3;
  }
  if (isset($_POST['auto_addreviewer']) && !empty($_POST['auto_addreviewer'])) {
    $ifArray[] = 4;
  }
  // Aktualisieren der Konferenz in der Datenbank
  if (isset($_POST['submit']) || isset($_POST['submit_adv'])) {
    // Prüfen, ob die Eingaben gültig sind.
    // Wenn die Eingaben gültig sind ist $strMessage leer.
    $strMessage=''; 
    if (empty($_POST['name'])
    ||  empty($paper_dl)
    ||  empty($review_dl)
    ||  empty($final_dl)
    ||  empty($start_date)
    ||  empty($abstract_dl))
    {
      $strMessage =  $strMessage.
                     "You have to fill in the fields <b>Title</b>, <b>Start Date</b> and <b>Deadlines</b>!\n";
    }
    if ((!empty($end_date)) && ($start_date > $end_date)) {
      $strMessage =  $strMessage.
                     "Your Start Date should be before your End Date!\n";
    }
    if ($abstract_dl > $paper_dl) {
      $strMessage =  $strMessage.
                     "Your Abstract Deadline should be before your Paper Deadline!\n";
    }
    if ($paper_dl > $final_dl) {
      $strMessage =  $strMessage.
                     "Your Paper Deadline should be before your Final Version Deadline!\n";
    }
    if ($final_dl > $start_date) {
      $strMessage =  $strMessage.
                     "Your Final Version Deadline should be before your Start Date!\n";
    }
    if ($paper_dl > $review_dl) {
      $strMessage =  $strMessage.
                     "Your Paper Deadline should be before your Review Deadline!\n";
    }
    if ((!empty($notification)) && ($review_dl > $notification)) {
      $strMessage =  $strMessage.
                     "Your Review Deadline should be before your Notification time!\n";
    }
    if ((!empty($notification)) && ($notification > $start_date)) {
      $strMessage =  $strMessage.
                     "Your Notification time should be before your Start Date!\n";
    }

    if ($review_dl > $start_date) {
      $strMessage =  $strMessage.
                     "Your Review Deadline should be before your Start Date!\n";
    }
    if ( !($min_reviews >= 0)){
      $strMessage =  $strMessage.
                     "Your minimum number of reviews should be greater or equel to zero!\n";
    }
    if ( !($min_papers >= 0)){
      $strMessage =  $strMessage.
                     "Your number of papers should be greater or equal to zero!\n";
    }
    if ( !($min_papers <= $max_papers)){
      $strMessage =  $strMessage.
                     "Your minimum number of papers should not be greater than the maximum number of paper!\n";
    }
    if ( !($min_reviews <= $def_reviews)){
      $strMessage =  $strMessage.
                     "Your minimum number of reviews should not be greater than the default number of reviews!\n";
    }
    if ( !(0 < $variance) || !($variance <= 100)){
      $strMessage =  $strMessage.
                     "Your ambiguity should be greater than zero and less or equal than hundred!\n";
    }
    if ( !(0 <= $auto_numrev )){
      $strMessage =  $strMessage.
                     "Your number of automatically added reviewers should be greater or equal than zero!\n";
    }
    // Versuche die Konferenz zu aktualisieren
    if ($strMessage=='') {
      $objCriterions = array();
      $objTopics = array();
      $objConferenceDetailed =
        new ConferenceDetailed(session('confid'),
                               $_POST['name'],
                               $_POST['homepage'],
                               $_POST['description'],
                               emptytime($start_date, 'Y-m-d'),
                               emptytime($end_date, 'Y-m-d'),
                               emptytime($abstract_dl, 'Y-m-d'),
                               emptytime($paper_dl, 'Y-m-d'),
                               emptytime($review_dl, 'Y-m-d'),
                               emptytime($final_dl, 'Y-m-d'),
                               emptytime($notification, 'Y-m-d'),
                               $_POST['min_reviews'],
                               $_POST['def_reviews'],
                               $_POST['min_papers'],
                               $_POST['max_papers'],
                               $_POST['variance']/100,
                               (!empty($_POST['auto_actaccount']) ? '1' : '0'),
                               (!empty($_POST['auto_paperforum']) ? '1' : '0'),
                               (!empty($_POST['auto_addreviewer']) ? '1' : '0'),
                               $_POST['auto_numreviewer'],
                               $objCriterions, $objTopics);
      $result = $myDBAccess->updateConference($objConferenceDetailed);
      if (!empty($result)) {
        // Erfolg
        $strMessage = 'Conference configuration has changed.';
      }
      else if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error updating conference.', $myDBAccess->getLastError());
      }
    }
  }
  // Oeffnen der erweiterten Einstellungen
  if ( isset($_POST['adv_config'])    || (isset($_POST['advanced']) &&
      !isset($_POST['simple_config']) && !isset($_POST['submit']))) {
      $content = new Template(TPLPATH.'edit_conference_ext.tpl');
  }
}
// Wenn keine Daten geliefert worden, uebernimm die Werte aus der Datenbank
else {
  $objConference = $myDBAccess->getConferenceDetailed(session('confid'));
  if ($myDBAccess->failed()) {
    error('Error during retrieving actual conference data.', $myDBAccess->getLastError());
  }
  else if (empty($objConference)) {
    error('Conference does not exist in database.', '');
  }
  $strContentAssocs['name']             = encodeText($objConference->strName);
  $strContentAssocs['description']      = encodeText($objConference->strDescription);
  $strContentAssocs['homepage']         = encodeURL($objConference->strHomepage);
  $strContentAssocs['start_date']       = encodeText($objConference->strStart);
  $strContentAssocs['end_date']         = encodeText($objConference->strEnd);
  $strContentAssocs['abstract_dl']      = encodeText($objConference->strAbstractDeadline);
  $strContentAssocs['paper_dl']         = encodeText($objConference->strPaperDeadline);
  $strContentAssocs['review_dl']        = encodeText($objConference->strReviewDeadline);
  $strContentAssocs['final_dl']         = encodeText($objConference->strFinalDeadline);
  $strContentAssocs['notification']     = encodeText($objConference->strNotification);
  $strContentAssocs['min_reviews']      = encodeText($objConference->intMinReviewsPerPaper);
  $strContentAssocs['def_reviews']      = encodeText($objConference->intDefaultReviewsPerPaper);
  $strContentAssocs['min_papers']       = encodeText($objConference->intMinNumberOfPapers);
  $strContentAssocs['max_papers']       = encodeText($objConference->intMaxNumberOfPapers);
  $strContentAssocs['variance']         = encodeText($objConference->fltCriticalVariance*100);
  $strContentAssocs['auto_numreviewer'] = encodeText($objConference->intNumberOfAutoAddReviewers);
  if (!empty($objConference->blnAutoActivateAccount)) {
    $ifArray[] = 2;
  }
  if (!empty($objConference->blnAutoOpenPaperForum)) {
    $ifArray[] = 3;
  }
  if (!empty($objConference->blnAutoAddReviewers)) {
    $ifArray[] = 4;
  }
}

$strContentAssocs['message'] = '';
if (isset($strMessage)) {
  $strContentAssocs['message'] = nl2br($strMessage);
  $ifArray[] = 9;
}

$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$_SESSION['menu'] = CHAIR;
$_SESSION['menuitem'] = 5;
include('./include/usermenu.inc.php');

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Configurate Conference';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Chair  |  Conference Config.';

$main->assign($strMainAssocs);
$main->parse();
$main->output();

?>