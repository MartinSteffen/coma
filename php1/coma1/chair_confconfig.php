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

  $strContentAssocs['id']               = encodeText($_POST['id']);
  $strContentAssocs['criterionsID']     = encodeText($_POST['criterionsID']);
  $strContentAssocs['topicsID']         = encodeText($_POST['topicsID']);
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
  $strTopics        = encodeTextArray($_POST['topics']);
  $strCriterions    = encodeTextArray($_POST['criterions']);
  $strCritDescripts = encodeTextArray($_POST['crit_descr']);
  $strCritMaxVals   = encodeTextArray($_POST['crit_max']);
  $strCritWeights   = encodeTextArray($_POST['crit_weight']);
  if (isset($_POST['advanced'])) {
    $intTopicNum = count($strTopics);
    $intCritNum  = count($strCriterions);
    $strTopics        = array();
    $strCriterions    = array();
    $strCritDescripts = array();
    $strCritMaxVals   = array();
    $strCritWeights   = array();
    for ($i = 0; $i < $intTopicNum; $i++) {
      $strTopics[] = encodeText($_POST['topic_name-'.($i+1)]);
    }
    for ($i = 0; $i < $intCritNum; $i++) {
      $strCriterions[]    = encodeText($_POST['crit_name-'.($i+1)]);
      $strCritDescripts[] = encodeText($_POST['crit_descr-'.($i+1)]);
      $strCritMaxVals[]   = encodeText($_POST['crit_max-'.($i+1)]);
      $strCritWeights[]   = encodeText($_POST['crit_weight-'.($i+1)]);
    }
  }
  if ( isset($_POST['adv_config'])    || (isset($_POST['advanced']) &&
      !isset($_POST['simple_config']) &&  !isset($_POST['submit']))) {
    $content = new Template(TPLPATH.'edit_conference_ext.tpl');
    $strContentAssocs['topic_lines'] = '';
    $strContentAssocs['crit_lines']  = '';
    for ($i = 0; $i < count($strTopics); $i++) {
      $topicForm = new Template(TPLPATH.'topic_listitem_edit.tpl');
      $strTopicAssocs = defaultAssocArray();
      $strTopicAssocs['topic_no']   = encodeText($i+1);
      $strTopicAssocs['topic_name'] = encodeText($strTopics[$i]);
      $topicForm->assign($strTopicAssocs);
      $topicForm->parse();
      $strContentAssocs['topic_lines'] .= $topicForm->getOutput();
    }
    for ($i = 0; $i < count($strCriterions); $i++) {
      $critForm = new Template(TPLPATH.'criterion_listitem_edit.tpl');
      $strCritAssocs = defaultAssocArray();
      $strCritAssocs['crit_no']     = encodeText($i+1);
      $strCritAssocs['crit_name']   = encodeText($strCriterions[$i]);
      $strCritAssocs['crit_descr']  = encodeText($strCritDescripts[$i]);
      $strCritAssocs['crit_max']    = encodeText($strCritMaxVals[$i]);
      $strCritAssocs['crit_weight'] = encodeText($strCritWeights[$i]);
      $critForm->assign($strCritAssocs);
      $critForm->parse();
      $strContentAssocs['crit_lines'] .= $critForm->getOutput();
    }
  }
  $strContentAssocs['topics']         = '';
  $strContentAssocs['criterions']     = '';
  $strContentAssocs['crit_max']       = '';
  $strContentAssocs['crit_descr']     = '';
  $strContentAssocs['crit_weight']    = '';
  $strContentAssocs['num_topics']     = encodeText(count($strTopics));
  $strContentAssocs['num_criterions'] = encodeText(count($strCriterions));
  for ($i = 0; $i < count($strTopics); $i++) {
    $strContentAssocs['topics'] .= (($i > 0) ? '|' : '') . encodeText($strTopics[$i]);
  }
  for ($i = 0; $i < count($strCriterions); $i++) {
    $strContentAssocs['criterions']  .= (($i > 0) ? '|' : '').encodeText($strCriterions[$i]);
    $strContentAssocs['crit_descr']  .= (($i > 0) ? '|' : '').encodeText($strCritDescripts[$i]);
    $strContentAssocs['crit_max']    .= (($i > 0) ? '|' : '').encodeText($strCritMaxVals[$i]);
    $strContentAssocs['crit_weight'] .= (($i > 0) ? '|' : '').encodeText($strCritWeights[$i]);
  }
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
      for ($i = 0; $i < count($strCriterions); $i++) {
        $objCriterions[$i] = new Topic($strCIds[$i], $strCriterions[$i], $strCritDescripts[$i], $strCritMaxVals[$i], $strCritWeights[$i]);
      }
      $objTopics = array();
      for ($i = 0; $i < count($strTopics); $i++) {
        $objTopics[$i] = new Topic($strTIds[$i], $strTopics[$i]);
      }
      $objConferenceDetailed =
        new ConferenceDetailed($_POST['id'],
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
      $result = updateConference($objConferenceDetailed);
      if (!empty($result)) {
        // Erfolg
        $strMessage = 'Conference configuration is changed.';
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
  $strContentAssocs['id']               = encodeText($objConference->intId);
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
  $strContentAssocs['criterions']     = '';
  $strContentAssocs['criterionsID']     = '';
  $strContentAssocs['crit_max']       = '';
  $strContentAssocs['crit_descr']     = '';
  $strContentAssocs['crit_weight']    = '';
  for ($i = 0; $i < count($objConference->objCriterions); $i++) {
    $strContentAssocs['criterionsID'] .= (($i > 0) ? '|' : '').encodeText($objConference->objCriterions[$i]->intId);
    $strContentAssocs['criterions']  .= (($i > 0) ? '|' : '').encodeText($objConference->objCriterions[$i]->strName);
    $strContentAssocs['crit_descr']  .= (($i > 0) ? '|' : '').encodeText($objConference->objCriterions[$i]->strDescription);
    $strContentAssocs['crit_max']    .= (($i > 0) ? '|' : '').encodeText($objConference->objCriterions[$i]->intMaxValue);
    $strContentAssocs['crit_weight'] .= (($i > 0) ? '|' : '').encodeText($objConference->objCriterions[$i]->fltWeight);
  }
  $strContentAssocs['topics']         = '';
  for ($i = 0; $i < count($objConference->objTopics); $i++) {
    $strContentAssocs['topicsID'] .= (($i > 0) ? '|' : '').encodeText($objConference->objTopics[$i]->intId);
    $strContentAssocs['topics'] .= (($i > 0) ? '|' : '') . encodeText($objConference->objTopics[$i]->strName);
  }
  $strContentAssocs['num_topics']     = encodeText(count($objConference->objTopics));
  $strContentAssocs['num_criterions'] = encodeText(count($objConference->objCriterions));

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

$actMenu = CHAIR;
$actMenuItem = 5;
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