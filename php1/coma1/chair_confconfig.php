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

$content = new Template(TPLPATH.'edit_conference.tpl');
$strContentAssocs = defaultAssocArray();
$ifArray = array();

// Teste, ob Daten mit der Anfrage des Benutzer mitgeliefert wurde.
if (isset($_POST['action'])) {

  // Konvertiere Zeit-Daten in was sinnvolles
  $abstract_dl = empty($_POST['abstract_dl']) ? '' : strtotime($_POST['abstract_dl']);
  $paper_dl = empty($_POST['paper_dl']) ? '' : strtotime($_POST['paper_dl']);
  $review_dl = empty($_POST['review_dl']) ? '' : strtotime($_POST['review_dl']);
  $final_dl = empty($_POST['final_dl']) ? '' : strtotime($_POST['final_dl']);
  $notification = empty($_POST['notification']) ? '' : strtotime($_POST['notification']);
  $start_date = empty($_POST['start_date']) ? '' : strtotime($_POST['start_date']);
  $end_date = empty($_POST['end_date']) ? '' : strtotime($_POST['end_date']);

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
  $strContentAssocs['variance']         = encodeText($_POST['variance']);
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
  if (isset($_POST['submit'])) {
    // auf korrkete Daten pruefen
    if (empty($_POST['name'])
    ||  empty($paper_dl)
    ||  empty($review_dl)
    ||  empty($final_dl)
    ||  empty($start_date)
    ||  empty($abstract_dl))
    {
      $strMessage = 'You have to fill in the fields <b>Title</b>, <b>Start Date</b>, '.
                    'and <b>Deadlines</b>!';
    }
    elseif ((!empty($end_date)) && ($start_date > $end_date)) {
      $strMessage = 'Your Start Date should be before your End Date!';
    }
    elseif ($abstract_dl > $paper_dl) {
      $strMessage = 'Your Abstract Deadline should be before your Paper Deadline!';
    }
    elseif ($paper_dl > $final_dl) {
      $strMessage = 'Your Paper Deadline should be before your Final Version Deadline!';
    }
    elseif ($final_dl > $start_date) {
      $strMessage = 'Your Final Version Deadline should be before your Start Date!';
    }
    elseif ($paper_dl > $review_dl) {
      $strMessage = 'Your Paper Deadline should be before your Review Deadline!';
    }
    elseif ((!empty($notification)) && ($review_dl > $notification)) {
      $strMessage = 'Your Review Deadline should be before your Notification time!';
    }
    elseif ((!empty($notification)) && ($notification > $start_date)) {
      $strMessage = 'Your Notification time should be before your Start Date!';
    }
    elseif ($review_dl > $start_date) {
      $strMessage = 'Your Notification time should be before your Start Date!';
    }
    // Versuche die Konferenz zu aktualisieren
    else {
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
                               $_POST['variance'],
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
      !isset($_POST['simple_config']) &&  !isset($_POST['submit']))) {
    $content = new Template(TPLPATH.'edit_conference_ext.tpl');
  }
}
// Wenn keine Daten geliefert worden, uebernimm die Werte aus der Datenbank
else {
  $objConference = $myDBAccess->getConferenceDetailed(session('confid'));
  if ($myDBAccess->failed()) {
    error('Error during retrieving actual conference data.', $myDBAccess->getLastError());
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
  $strContentAssocs['variance']         = encodeText($objConference->fltCriticalVariance);
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
  $strContentAssocs['message'] = $strMessage;
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