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

$content = new Template(TPLPATH.'create_conference.tpl');
$strContentAssocs = defaultAssocArray();
$ifArray = array();

// Teste, ob Daten mit der Anfrage des Benutzer mitgeliefert wurde.
// siehe von der Struktur her main_profile.php
if (isset($_POST['action'])) {

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
  $strContentAssocs['description']      = encodeText($_POST['description'], false);
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
      if (!isset($_POST['del_topic-'.($i+1)])) {
        $strTopics[] = $_POST['topic_name-'.($i+1)];
      }
    }
    for ($i = 0; $i < $intCritNum; $i++) {
      if (!isset($_POST['del_crit-'.($i+1)])) {
        $strCriterions[]    = $_POST['crit_name-'.($i+1)];
        $strCritDescripts[] = $_POST['crit_descr-'.($i+1)];
        $strCritMaxVals[]   = $_POST['crit_max-'.($i+1)];
        $strCritWeights[]   = $_POST['crit_weight-'.($i+1)];
      }
    }
    if (isset($_POST['add_topic']) && !empty($_POST['topic_name'])) {
        $strTopics[] = $_POST['topic_name'];
    }
    if (isset($_POST['add_crit']) && !empty($_POST['crit_name'])) {
      $strCriterions[]    = $_POST['crit_name'];
      $strCritDescripts[] = $_POST['crit_descr'];
      $strCritMaxVals[]   = $_POST['crit_max'];
      $strCritWeights[]   = $_POST['crit_weight'];
    }
  }
  if ( isset($_POST['adv_config'])    || (isset($_POST['advanced']) &&
      !isset($_POST['simple_config']) &&  !isset($_POST['submit']))) {
    $content = new Template(TPLPATH.'create_conference_ext.tpl');
    $strContentAssocs['topic_lines'] = '';
    $strContentAssocs['crit_lines']  = '';
    for ($i = 0; $i < count($strTopics); $i++) {
      $topicForm = new Template(TPLPATH.'topic_listitem.tpl');
      $strTopicAssocs = defaultAssocArray();
      $strTopicAssocs['topic_no']   = encodeText($i+1);
      $strTopicAssocs['topic_name'] = encodeText($strTopics[$i]);
      $topicForm->assign($strTopicAssocs);
      $topicForm->parse();
      $strContentAssocs['topic_lines'] .= $topicForm->getOutput();
    }
    for ($i = 0; $i < count($strCriterions); $i++) {
      $critForm = new Template(TPLPATH.'criterion_listitem.tpl');
      $strCritAssocs = defaultAssocArray();
      $strCritAssocs['crit_no']     = encodeText($i+1);
      $strCritAssocs['crit_name']   = encodeText($strCriterions[$i]);
      $strCritAssocs['crit_descr']  = encodeText($strCritDescripts[$i], false);
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
    $strContentAssocs['crit_descr']  .= (($i > 0) ? '|' : '').encodeText($strCritDescripts[$i], false);
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
  // Anlegen der Konferenz in der Datenbank
  if (isset($_POST['submit'])) {
    // Prüfen, ob die Eingaben gültig sind.
    // Wenn die Eingaben gültig sind, so ist $strMessage leer.
    $strMessage=''; 
    if (empty($_POST['name'])
    ||  empty($paper_dl)
    ||  empty($review_dl)
    ||  empty($final_dl)
    ||  empty($start_date)
    ||  empty($abstract_dl))
    {
      $strMessage .= "You have to fill in the fields <b>Title</b>, <b>Start Date</b> and <b>Deadlines</b>!\n";
    }
    if ((!empty($end_date)) && ($start_date > $end_date)) {
      $strMessage .= "Your Start Date should be before your End Date!\n";
    }
    if ($abstract_dl > $paper_dl) {
      $strMessage .= "Your Abstract Deadline should be before your Paper Deadline!\n";
    }
    if ($paper_dl > $final_dl) {
      $strMessage .= "Your Paper Deadline should be before your Final Version Deadline!\n";
    }
    if ($final_dl > $start_date) {
      $strMessage .= "Your Final Version Deadline should be before your Start Date!\n";
    }
    if ($paper_dl > $review_dl) {
      $strMessage .= "Your Paper Deadline should be before your Review Deadline!\n";
    }
    if ((!empty($notification)) && ($review_dl > $notification)) {
      $strMessage .= "Your Review Deadline should be before your Notification time!\n";
    }
    if ((!empty($notification)) && ($notification > $start_date)) {
      $strMessage .= "Your Notification time should be before your Start Date!\n";
    }
    if ($review_dl > $start_date) {
      $strMessage .= "Your Notification time should be before your Start Date!\n";
    }
    if ( !($min_reviews >= 0)){
      $strMessage .= "Your minimum number of reviews should be greater or equel to zero!\n";
    }
    if ( !($min_papers >= 0)){
      $strMessage .= "Your number of papers should be greater or equal to zero!\n";
    }
    if ( !($min_papers <= $max_papers)){
      $strMessage .= "Your minimum number of papers should not be greater than the maximum number of paper!\n";
    }
    if ( !($min_reviews <= $def_reviews)){
      $strMessage .= "Your minimum number of reviews should not be greater than the default number of reviews!\n";
    }
    if ( !(0 < $variance) || !($variance <= 100)){
      $strMessage .= "Your ambiguity should be greater than zero and less or equal than hundred!\n";
    }
    if ( !(0 <= $auto_numrev )){
      $strMessage .= "Your number of automatically added reviewers should be greater or equal than zero!\n";
    }

    /**
     * Durch Löschen der Defaultwerte ist es möglich versehentlich 
     * ein leeres Kriterium in die Datenbank einzutragen !!!
     */
    foreach ($strCriterions as $key => $crit){
      if ( empty($crit) ){
	      unset($crit);
      }
    } 

    foreach ($strCritMaxVals as $key => $critMax){
      if ( !(0 <= $critMax )){
        $strMessage .= "The maximum value of the criterion '{$strCriterions[$key]}' shoud be greater or equal than zero!\n";
      }
    }

    foreach ($strCritWeights as $key => $critWeights){
      if ( !(0 < $critWeights )){
        $strMessage .= "The weight of the criterion '{$strCriterions[$key]}'shoud be greater then zero!\n";
      }
    }
 
    
    foreach ($strCritWeights as $key => $critWeights) {
      if ( !($critWeights <= 1 )){
        $strMessage .= "The weight of the criterion '{$strCriterions[$key]}' shoud be less then one!\n";
      }
    }
    // Versuche die neue Konferenz einzutragen, wenn die Eingaben nicht fehlerhaft sind
    if (empty($strMessage)) { // keine Fehler
      $intConfId = $myDBAccess->addConference($_POST['name'],
                                              $_POST['homepage'],
                                              $_POST['description'],
                                              emptytime($abstract_dl, 'Y-m-d'),
                                              emptytime($paper_dl, 'Y-m-d'),
                                              emptytime($review_dl, 'Y-m-d'),
                                              emptytime($final_dl, 'Y-m-d'),
                                              emptytime($notification, 'Y-m-d'),
                                              emptytime($start_date, 'Y-m-d'),
                                              emptytime($end_date, 'Y-m-d'),
                                              $_POST['min_reviews'],
                                              $_POST['def_reviews'],
                                              $_POST['min_papers'],
                                              $_POST['max_papers'],
                                              $_POST['variance']/100,
                                              (!empty($_POST['auto_actaccount']) ? '1' : '0'),
                                              (!empty($_POST['auto_paperforum']) ? '1' : '0'),
                                              (!empty($_POST['auto_addreviewer']) ? '1' : '0'),
                                              $_POST['auto_numreviewer'],
                                              $strTopics, $strCriterions, $strCritDescripts,
                                              $strCritMaxVals, $strCritWeights);
      if ($myDBAccess->failed()) {
        // Datenbankfehler?
        error('Error during creating conference.', $myDBAccess->getLastError());
      }
      // Trage den Ersteller als Chair in die Konferenz ein
      else if (!empty($intConfId)) {
        $resultRole = $myDBAccess->addRole(session('uid'), CHAIR, $intConfId);
        if ($myDBAccess->failed()) {
          // Datenbankfehler?
          error('Error during creating chair for conference. '.
                'Database may contain inaccessible conference. ', $myDBAccess->getLastError());
        }
        else {
          // Erfolg (also anderes Template)
          $content = new Template(TPLPATH.'confirm_conference.tpl');
          $strContentAssocs['return_page'] = 'main_conferences.php';
          $objConference = new Conference(0,'','','', emptytime($start_date), emptytime($end_date));
          $strContentAssocs['date'] = encodeText($objConference->getDateString());
          $ifArray = array();
        }
      }
    }
  }
}
// Wenn keine Daten geliefert wurden, nimm die Defaultwerte
else {
  $ifArray[] = 2;
  $ifArray[] = 3;
  $strContentAssocs['min_papers']       = '1';
  $strContentAssocs['max_papers']       = '100';
  $strContentAssocs['min_reviews']      = '3';
  $strContentAssocs['def_reviews']      = '4';
  $strContentAssocs['auto_numreviewer'] = '2';
  $strContentAssocs['variance']         = '10';
  $strContentAssocs['num_topics']       = '3';
  $strContentAssocs['num_criterions']   = '2';
  $strContentAssocs['topics']           = 'Informatics|Mathematics|Electronics';
  $strContentAssocs['criterions']       = 'Content|Form';
  $strContentAssocs['crit_max']         = '5|5';
  $strContentAssocs['crit_descr']       = 'Aspects of the content|Aspects of the outer form';
  $strContentAssocs['crit_weight']      = '0.7|0.3';
}

$strContentAssocs['message'] = '';
if (!empyt($strMessage)) {
  $strContentAssocs['message'] = nl2tag($strMessage);
  $ifArray[] = 9;
}

$strContentAssocs['if'] = $ifArray;
$content->assign($strContentAssocs);

$menu = new Template(TPLPATH.'mainmenu.tpl');
$strMenuAssocs = defaultAssocArray();
$strMenuAssocs['if'] = array(3);
$menu->assign($strMenuAssocs);

$main = new Template(TPLPATH.'frame.tpl');
$strMainAssocs = defaultAssocArray();
$strMainAssocs['title'] = 'Create new Conference';
$strMainAssocs['content'] = &$content;
$strMainAssocs['menu'] = &$menu;
$strMainAssocs['navigator'] = encodeText(session('uname')).'  |  Create conference';

$main->assign($strMainAssocs);
$main->parse();
$main->output();
?>